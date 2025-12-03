<?php
namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\MemberAccount;
use App\Models\Slip;
use App\Models\SlipItem;
use App\Models\MemberTransaction;
use App\Services\MemberService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;


class WebController extends Controller
{
    protected $memberService;

    public function __construct(MemberService $memberService)
    {
        $this->memberService = $memberService;
    }

    public function cover(Request $request)
    {
        return view('cover');
    }

    public function index(Request $request)
    {
        $notshow = $request->cookie('notshow');
        if ($notshow === 'true') {
            $notshow = true;
        } else {
            $notshow = false;
        }
        return view('index', ['notshow' => $notshow]);
    }

    public function signup(Request $request)
    {
        $provinces = [
            'กรุงเทพมหานคร',
            'กระบี่',
            'กาญจนบุรี',
            'กาฬสินธุ์',
            'กำแพงเพชร',
            'ขอนแก่น',
            'จันทบุรี',
            'ฉะเชิงเทรา',
            'ชลบุรี',
            'ชัยนาท',
            'ชัยภูมิ',
            'ชุมพร',
            'เชียงราย',
            'เชียงใหม่',
            'ตรัง',
            'ตราด',
            'ตาก',
            'นครนายก',
            'นครปฐม',
            'นครพนม',
            'นครราชสีมา',
            'นครศรีธรรมราช',
            'นครสวรรค์',
            'นนทบุรี',
            'นราธิวาส',
            'น่าน',
            'บึงกาฬ',
            'บุรีรัมย์',
            'ปทุมธานี',
            'ประจวบคีรีขันธ์',
            'ปราจีนบุรี',
            'ปัตตานี',
            'พระนครศรีอยุธยา',
            'พะเยา',
            'พังงา',
            'พัทลุง',
            'พิจิตร',
            'พิษณุโลก',
            'เพชรบุรี',
            'เพชรบูรณ์',
            'แพร่',
            'ภูเก็ต',
            'มหาสารคาม',
            'มุกดาหาร',
            'แม่ฮ่องสอน',
            'ยโสธร',
            'ยะลา',
            'ร้อยเอ็ด',
            'ระนอง',
            'ระยอง',
            'ราชบุรี',
            'ลพบุรี',
            'ลำปาง',
            'ลำพูน',
            'เลย',
            'ศรีสะเกษ',
            'สกลนคร',
            'สงขลา',
            'สตูล',
            'สมุทรปราการ',
            'สมุทรสงคราม',
            'สมุทรสาคร',
            'สระแก้ว',
            'สระบุรี',
            'สิงห์บุรี',
            'สุโขทัย',
            'สุพรรณบุรี',
            'สุราษฎร์ธานี',
            'สุรินทร์',
            'หนองคาย',
            'หนองบัวลำภู',
            'อ่างทอง',
            'อำนาจเจริญ',
            'อุดรธานี',
            'อุตรดิตถ์',
            'อุทัยธานี',
            'อุบลราชธานี',
        ];
        $line = Session::get('tempmember');
        return view('signup', ['lineid' => $line->sub, 'provinces' => $provinces]);
    }

    public function storemember(Request $request)
    {
        $cmember = session('member');
        if ($cmember) {
            return redirect()->route('member');
        }
        //check duplicate account
        $account = MemberAccount::firstOrNew(['authid' => $request->lineid, 'application' => config('app.name')]);
        if (isset($account->id)) {
            Session::forget('member');
            return redirect()->route('home');
        }
        $request->phone = filterChar($request->phone, 'phone');
        $member = Member::firstOrNew(['phone' => $request->phone]);
        if (!isset($member->id)) {
            $member->firstname = $request->firstname;
            $member->lastname = $request->lastname;
            $member->save();
        }
        $account->type = 'line';
        $account->application = config('app.name');
        $account->phone = $request->phone;
        $account->authid = $request->lineid;
        $account->member = $member->id;
        $account->verify = true;
        $account->save();
        $this->memberService->memberAddress($member, $request);
        $member->point = $this->memberService->memberPoint($member->id, 'init');
        $gigya = createGigyaData($request);
        if (isset($gigya->unileverId) && $gigya->unileverId != '') {
            setcookie("UnileverId", $gigya->unileverId, time() + 30 * 24 * 60 * 60, "/", "", false, false);
        }
        $member->phone = $request->phone;
        Session::forget('tempmember');
        Session::put('member', $member);
        addMemberHistory($member->id, 'member', 'signup new member');
        if ($request->type == 'line') {
            addMemberHistory($member->id, 'member', 'signin via line');
        } else {
            addMemberHistory($member->id, 'member', 'signin via password');
        }
        return redirect()->route('member')->with('success', 'newaccount');
    }

    public function storememberpassword(Request $request)
    {
        $validatorRule = [];
        $validatorRule['firstname'] = ['required', 'string'];
        $validatorRule['lastname'] = ['required', 'string'];
        $validatorRule['phone'] = ['required', 'string'];
        // $validatorRule['email'] = ['required', 'email'];
        $validatorRule['password'] = ['required', 'confirmed', Password::min(8)->mixedCase()->letters()->numbers()];

        $validator = Validator::make($request->all(), $validatorRule);
        if ($validator->fails()) {
            return response()->json([
                'result' => false,
                'messages' => $validator->errors(),
            ], 400);
        }
        //check duplicate account
        $request->phone = filterChar($request->phone, 'phone');
        $account = MemberAccount::firstOrNew(['phone' => $request->phone, 'application' => config('app.name'), 'type' => 'password', 'verify' => true]);
        if ($account->id) {
            return response()->json([
                'result' => false,
                'messages' => [
                    'phone' => ['this phone is already exist'],
                ],
            ], 400);
        }
        $member = Member::firstOrNew(['phone' => $request->phone]);
        if (!isset($member->id)) {
            $member->firstname = $request->firstname;
            $member->lastname = $request->lastname;
            $member->save();
        }
        $account->password = Hash::make($request->password);
        $account->member = $member->id;
        $account->verify = true;
        $account->save();
        $this->memberService->memberAddress($member, $request);
        $member->point = $this->memberService->memberPoint($member->id, 'init');
        Session::forget('tempmember');
        Session::put('member', $member);
        addMemberHistory($member->id, 'member', 'signup new member');
        if ($request->type == 'line') {
            addMemberHistory($member->id, 'member', 'signin via line');
        } else {
            addMemberHistory($member->id, 'member', 'signin via password');
        }
        return $member;
    }

    // public function sendEmailForgotPassword(Request $request)
    // {
    //     $memberA = MemberAccount::where('email', $request->email)
    //         ->where('type', 'password')
    //         ->where('application', config('app.name'))
    //         ->first();
    //     if (!$memberA) {
    //         return redirect()->route('forgot')->with('error', 'ไม่พบ email ในระบบ');
    //     }
    //     $memberA->token = str_replace('-', '', Str::uuid());
    //     $memberA->tokendate = Carbon::now()->toDateTimeString();
    //     $memberA->save();
    //     $member = Member::find($memberA->member);
    //     Mail::to($request->email)->send(new Forgotpassword($member, $memberA));
    //     return redirect()->route('forgot')->with('success', 'ส่ง email สำหรับ recovery password เรียบร้อยแล้ว.โปรดตรวจสอบ email.');
    // }

    public function term(Request $request)
    {
        return view('term', ['term' => $request->term]);
    }

    public function upload(Request $request)
    {
        return view('upload', ['transactions' => []]);
    }

    public function enlarge(Request $request)
    {
        return view('member.enlarge', ['url' => $request->url]);
    }

    public function storeupload(Request $request)
    {
        $slipId = Str::uuid();
        $uploadedFile = $request->file('slip');
        $extension = $uploadedFile->getClientOriginalExtension();
        $filename = $slipId . '.' . $extension;
        if (!Storage::disk('azure')->putFileAs(config('app.azurepath'), $uploadedFile, $filename)) {
            return ["result" => false];
        }
        $data = json_decode($request->data);
        $total = 0;
        foreach ($data->products as $product) {
            if (filter_var($product->verify, FILTER_VALIDATE_BOOLEAN)) {
                $total = $total + intval($product->total);
            }
        }
        $slip = new Slip;
        $slip->id = $slipId;
        $slip->receiptno = $data->receiptNo;
        $slip->ocrmerchantname = $data->merchantName;
        $slip->merchantname = $data->merchantName;
        $slip->datetime = $data->dateTime;
        $slip->total = $total;
        $slip->point = floor(intval($total) / intval(config('app.bathperpoint')));
        $slip->status = 'process';
        $slip->application = config('app.name');
        $slip->member = Session::get('member')->id;
        $slip->slipname = $filename;
        $slip->ocrreceiptno = $data->receiptNo;
        $slip->ocrtotal = $total;
        $slip->is_valid = filter_var($data->isValid, FILTER_VALIDATE_BOOLEAN);
        $slip->save();
        foreach ($data->products as $product) {
            $slipItem = new SlipItem;
            $slipItem->name = $product->productName;
            $slipItem->amount = intval($product->amount);
            $slipItem->price = $product->total;
            $slipItem->verify = filter_var($product->verify, FILTER_VALIDATE_BOOLEAN);
            $slipItem->slip = $slipId;
            $slipItem->ocrname = $product->productName;
            $slipItem->ocramount = intval($product->amount);
            $slipItem->ocrprice = $product->total;
            $slipItem->ocrverify = filter_var($product->verify, FILTER_VALIDATE_BOOLEAN);
            $slipItem->application = config('app.name');
            $slipItem->save();
        }
        //add transaction
        $memberTransaction = new MemberTransaction;
        $memberTransaction->member = Session::get('member')->id;
        $memberTransaction->slip = $slip->id;
        $memberTransaction->application = config('app.name');
        $memberTransaction->save();
        addMemberHistory(Session::get('member')->id, 'member', 'upload slip');
        return true;
    }
}
