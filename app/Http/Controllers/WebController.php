<?php
namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\MemberAccount;
use App\Models\MemberPoint;
use App\Services\MemberService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
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
        $cmember = session('member');
        if ($cmember) {
            return redirect()->route('member');
        }
        $notshow = $request->cookie('notshow_breeze2025YearEnd');
        if ($notshow === 'true') {
            $notshow = true;
        } else {
            $notshow = false;
        }
        if (now()->isAfter('2025-12-19 23:59:59')) {
            return redirect()->route('annoucement');
        }
        return view('index', ['notshow' => $notshow]);
    }

    public function signin(Request $request)
    {
        return view('signin');
    }

    public function storesignin(Request $request)
    {
        $validatorRule = [];
        $validatorRule['phone'] = ['required', 'string'];
        $validatorRule['password'] = ['required', 'string'];
        $validator = Validator::make($request->all(), $validatorRule);
        if ($validator->fails()) {
            return response()->json([
                'result' => false,
                'messages' => $validator->errors(),
            ], 400);
        }
        //check duplicate account
        $phone = filterChar($request->phone, 'phone');
        $account = MemberAccount::where('phone', $phone)
            ->where('application', config('app.name'))
            ->where('type', 'password')
            ->where('verify', true)
            ->first();
        if (!$account) {
            return response()->json([
                'result' => false,
                'messages' => [
                    'signin' => ['เบอร์โทรศัพท์ไม่ถูกต้อง'],
                ],
            ], 400);
        }
        if (!Hash::check($request->password, $account->password)) {
            return response()->json([
                'result' => false,
                'messages' => [
                    'signin' => ['รหัสผ่านไม่ถูกต้อง'],
                ],
            ], 400);
        }
        $member = Member::find($account->member);
        $member->point = 0;
        $memberPoint = MemberPoint::where('member', $member->id)
            ->where('application', config('app.name'))
            ->first();
        if ($memberPoint) {
            $member->point = $memberPoint->point;
        }
        session(['member' => $member]);
        addMemberHistory($member->id, 'member', 'signin via line');
        return ['result' => true];
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
        return view('signup', ['provinces' => $provinces]);
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
        $gigya = createGigyaData($request);
        if (isset($gigya->unileverId) && $gigya->unileverId != '') {
            setcookie("UnileverId", $gigya->unileverId, time() + 30 * 24 * 60 * 60, "/", "", false, false);
        }
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

    public function term(Request $request)
    {
        return view('term', ['term' => $request->term]);
    }

    public function annoucement(Request $request)
    {
        $totalTopspender = $this->memberService->getTopSpender() ?? [];
        return view('annoucement', ['totalTopspender' => $totalTopspender]);
    }
}
