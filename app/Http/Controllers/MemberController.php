<?php
namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\MemberTransaction;
use App\Models\MemberAddress;
use App\Models\MemberPoint;
use App\Models\Topspender;
use App\Models\Slip;
use App\Services\MemberService;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Lang;
use Str;

class MemberController extends Controller
{
    protected $memberService;

    public function __construct(MemberService $memberService)
    {
        $this->memberService = $memberService;
    }

    public function instructions(Request $request)
    {
        return view('member.instructions');
    }

    public function index(Request $request)
    {
        $member = Session::get('member');
        $point = 0;
        if ($member) {
            $pointData = DB::table('topspenders')
                ->select('point')
                ->where('member', $member->id)
                ->where('application', config('app.name'))
                ->first();
            $point = $pointData ? $pointData->point : 0;
        }
        $memberData = MemberPoint::firstOrNew(['member' => $member->id, 'application' => config('app.name')]);
        $pointRedeem = $memberData->point;
        $transactions = DB::table('member_transactions')
            ->select(
                'member_transactions.id',
                'slips.id as slipid',
                'slips.system',
                'slips.status',
                'member_transactions.created_at',
                'slips.merchantname',
                'slips.point as slippoints',
                'slips.total as sliptotal',
                'slips.receiptno',
                'slips.rejectreason'
            )
            ->leftJoin('slips', 'member_transactions.slip', '=', 'slips.id')
            ->where('member_transactions.member', $member->id)
            ->where('member_transactions.application', config('app.name'))
            ->orderBy('member_transactions.created_at', 'desc')
            ->limit(5)
            ->get();
        $topSpender = $this->memberService->getTopSpender() ?? [];
        return view('member.member', ['point' => $point, 'transactions' => $transactions, 'topSpenders' => $topSpender, 'pointRedeem' => $pointRedeem]);
    }

    public function upload(Request $request)
    {
        $transactions = DB::table('member_transactions')
            ->select(
                'member_transactions.id',
                'slips.id as slipid',
                'slips.system',
                'slips.status',
                'member_transactions.created_at',
                'slips.point as slippoints',
                'slips.total as sliptotal',
                'slips.rejectreason'
            )
            ->leftJoin('slips', 'member_transactions.slip', '=', 'slips.id')
            ->where('member_transactions.member', Session::get('member')->id)
            ->where('member_transactions.application', config('app.name'))
            ->orderBy('member_transactions.created_at', 'desc')
            ->limit(5)
            ->get();
        return view('member.upload', ['transactions' => $transactions]);
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
            return response()->json([
                'result' => false,
                'messages' => 'ไม่สามารถอัพโหลดไฟล์ได้',
            ], 400);
        }
        $url = Storage::disk('azure')->url(config('app.azurepath') . '/' . $filename);
        $ansArrs = qnada([], $request);
        $merchant = $request->merchant_name;
        if ($request->merchant_name === "1263") {
            $merchant = "Shopee";
        } else if ($request->merchant_name === "1262") {
            $merchant = " Lazada";
        } else if ($request->merchant_name === "3657") {
            $merchant = "Tiktok";
        }
        $slip = new Slip;
        $slip->id = $slipId;
        $slip->receiptno = $request->order_id;
        $slip->ocrmerchantname = json_encode($ansArrs, JSON_UNESCAPED_UNICODE);
        $slip->merchantname = $merchant;
        $slip->total = $request->amount;
        $slip->point = 0;
        $slip->status = 'process';
        $slip->application = config('app.name');
        $slip->member = Session::get('member')->id;
        $slip->slipname = $filename;
        $slip->url = $url;
        $slip->save();
        //add transaction
        $memberTransaction = new MemberTransaction;
        $memberTransaction->member = Session::get('member')->id;
        $memberTransaction->slip = $slip->id;
        $memberTransaction->application = config('app.name');
        $memberTransaction->save();
        addMemberHistory(Session::get('member')->id, 'member', 'upload slip');
        sendMerchantNameGigya($request);
        return ["result" => true];
    }

    public function history(Request $request)
    {
        $member = Session::get('member');
        $transactions = DB::table('member_transactions')
            ->select(
                'member_transactions.id',
                'slips.id as slipid',
                'slips.system',
                'slips.status',
                'member_transactions.created_at',
                'slips.point as slippoints',
                'slips.total as sliptotal',
                'slips.receiptno',
                'slips.rejectreason',
                'slips.merchantname',
                'slips.url'
            )
            ->leftJoin('slips', 'member_transactions.slip', '=', 'slips.id')
            ->where('member_transactions.member', $member->id)
            ->where('member_transactions.application', config('app.name'))
            ->orderBy('member_transactions.created_at', 'desc')
            ->limit(11)
            ->get();
        $slipRejects = DB::table('slips')
            ->select(
                'id as slipid',
                'receiptno',
                'merchantname',
                'point as slippoints',
                'system',
                'rejectreason',
                'member',
                'application',
                'status',
                'created_at'
            )
            ->where('status', 'reject')
            ->where('member', Session::get('member')->id)
            ->where('application', config('app.name'))
            ->limit(11)
            ->orderBy('created_at', 'desc')
            ->get();
        $slipPendings = DB::table('slips')
            ->select(
                'id as slipid',
                'receiptno',
                'merchantname',
                'point as slippoints',
                'system',
                'rejectreason',
                'member',
                'application',
                'status',
                'created_at'
            )
            ->where('status', 'process')
            ->where('member', Session::get('member')->id)
            ->where('application', config('app.name'))
            ->limit(11)
            ->orderBy('created_at', 'desc')
            ->get();
        return view('member.history', ['transactions' => $transactions, 'slipRejects' => $slipRejects, 'slipPendings' => $slipPendings]);
    }

    public function loadhistory(Request $request)
    {
        $result = new \stdClass;
        $result->next = true;
        $datas = [];
        $limit = 10;
        $total = 0;
        $start = ($request->page - 1) * $limit;

        switch ($request->type) {
            case 'all':
                $datas = DB::table('member_transactions')
                    ->select(
                        'member_transactions.id',
                        'slips.id as slipid',
                        'slips.receiptno',
                        'slips.merchantname',
                        'slips.total as sliptotal',
                        'slips.system',
                        'slips.status',
                        'member_transactions.created_at',
                        'slips.point as slippoints',
                        'slips.rejectreason'
                    )
                    ->leftJoin('slips', 'member_transactions.slip', '=', 'slips.id')
                    ->where('member_transactions.member', Session::get('member')->id)
                    ->where('member_transactions.application', config('app.name'))
                    ->offset($start)
                    ->limit($limit)
                    ->orderBy('member_transactions.created_at', 'desc')
                    ->get();

                $total = MemberTransaction::where('member', Session::get('member')->id)
                    ->where('application', config('app.name'))
                    ->count();

                foreach ($datas as $data) {
                    $data->created_at = date('d/m/Y H:i', strtotime($data->created_at));
                    $data->resend_url = route('upload');
                    $data->type = 'slip';
                    $data->title = Lang::get('historyitem.sendreceipt');

                    switch ($data->status) {
                        case 'process':
                            $data->point = null;
                            break;

                        case 'reject':
                            $data->point = null;
                            break;

                        case 'approve':
                            $data->point = (int) $data->slippoints;
                            break;
                    }
                }
                break;
            case 'slip':
                $datas = DB::table('slips')
                    ->select(
                        'id',
                        'receiptno',
                        'merchantname',
                        'total as sliptotal',
                        'point as slippoints',
                        'rejectreason',
                        'status',
                        'created_at'
                    )
                    ->where('status', $request->status)
                    ->where('member', Session::get('member')->id)
                    ->where('application', config('app.name'))
                    ->offset($start)
                    ->limit($limit)
                    ->orderBy('created_at', 'desc')
                    ->get();

                $total = Slip::where('status', $request->status)
                    ->where('member', Session::get('member')->id)
                    ->where('application', config('app.name'))
                    ->count();

                foreach ($datas as $data) {

                    $data->created_at = date('d/m/Y H:i', strtotime($data->created_at));
                    $data->title = Lang::get('historyitem.sendreceipt');
                    $data->type = 'slip';
                    $data->resend_url = route('upload');

                    switch ($data->status) {
                        case 'process':
                            $data->point = null;
                            break;

                        case 'reject':
                            $data->point = null;
                            break;

                        case 'approve':
                            $data->point = (int) $data->slippoints;
                            break;
                    }
                }
                break;
        }

        if ($request->page >= ceil($total / $limit)) {
            $result->next = false;
        }

        $result->datas = $datas;
        return $result;
    }

    public function profile(Request $request)
    {
        $member = Session::get('member');
        $address = MemberAddress::firstOrNew(['member' => $member->id, 'application' => config('app.name')]);
        $provinces = [
            'กระบี่',
            'กรุงเทพมหานคร',
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
        return view('member.profile', ['member' => $member, 'address' => $address, 'provinces' => $provinces]);
    }

    public function profilestore(Request $request)
    {
        //current point
        $point = Session::get('member')->point;
        $member = Member::find(Session::get('member')->id);
        $member->firstname = $request->firstname;
        $member->lastname = $request->lastname;
        $member->save();
        $member->point = $point;
        Session::put('member', $member);
        //filter phone input
        $request->phone = filterChar($request->phone, 'phone');
        $this->memberService->memberAddress($member, $request);
        addMemberHistory($member->id, 'member', 'update profile');
        $fileData = readCustomFile('topspender.json');
        if ($fileData) {
            //create topspender file
            foreach ($fileData as $data) {
                if ($data->id == $member->id) {
                    $topspenders = Topspender::select('members.id', 'members.firstname', 'topspenders.point')->
                        join('members', 'topspenders.member', '=', 'members.id')
                        ->orderBy('topspenders.point', 'desc')
                        ->orderBy('topspenders.updated_at', 'asc')
                        ->limit(10)->get();
                    createCustomFile("topspender.json", $topspenders);
                    break;
                }
            }
        }
        return redirect()->route('profile')->with('success', 'แก้ไขข้อมูลโปรไฟล์แล้ว');
    }

    public function topspender(Request $request)
    {
        $totalTopspender = $this->memberService->getTopSpender();
        $member = Session::get('member');
        $point = DB::table('topspenders')
            ->select('point')
            ->where('member', $member->id)
            ->where('application', config('app.name'))
            ->orderBy('enddate', 'asc')
            ->first();

        $points[] = $point->point ?? 0;
        return view('member.topspender', ['totalTopspender' => $totalTopspender, 'point' => $points]);
    }

    public function rule(Request $request)
    {
        return view('member.rule');
    }

    public function signout(Request $request)
    {
        Session::forget('member');
        return redirect()->route('home');
    }

}
