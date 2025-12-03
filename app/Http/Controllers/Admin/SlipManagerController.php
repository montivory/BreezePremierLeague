<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Member;
use App\Models\Slip;
use App\Models\Topspender;
use App\Services\MemberService;
use Auth;
use Illuminate\Http\Request;

class SlipManagerController extends Controller
{

    protected $memberService;

    public function __construct(MemberService $memberService)
    {
        $this->memberService = $memberService;
    }

    public function index(Request $request)
    {
        $status = 'process';
        if (isset($request->status)) {
            $status = $request->status;
        }
        return view('admin.slips.index', ['status' => $status]);
    }

    public function verify($id, Request $request)
    {
        $slip = Slip::find($id);
        $member = Member::find($slip->member);
        $systemFlag = '';
        if (Slip::where('receiptno', $slip->ocrreceiptno)->where('status', 'approve')->where('id', '<>', $id)->where('application', config('app.name'))->count() > 0) {
            $systemFlag = 'already exist';
        }
        if (empty($slip->ocrreceiptno)) {
            $systemFlag = 'not found receipt no';
        }
        return view('admin.slips.verify', ['slip' => $slip, 'member' => $member, 'systemFlag' => $systemFlag]);
    }

    public function viewslip($id, Request $request)
    {
        $slip = Slip::find($id);
        $member = Member::find($slip->member);
        return view('admin.slips.view', ['slip' => $slip, 'member' => $member]);
    }

    public function adminsearchslip(Request $request)
    {
        $slipId = $request->slip;
        $receiptno = $request->receiptno;
        $count = Slip::where('receiptno', $receiptno)->where('id', '<>', $slipId)->where('application', config('app.name'))->where('status', 'approve')->count();
        $result = new \stdClass;
        $result->result = true;
        if ($count > 0) {
            $result->result = false;
        }
        return $result;
    }

    public function store(Request $request)
    {
        $slipId = $request->slip;
        $receiptno = $request->receiptno;
        //check duplicate recipt no
        $count = Slip::where('receiptno', $receiptno)->where('id', '<>', $slipId)->where('application', config('app.name'))->where('status', 'approve')->count();
        if ($count > 0) {
            return false;
        }
        $slip = Slip::find($slipId);
        if ($slip) {
            $admin = Auth::user();
            $slip->receiptno = $receiptno;
            $slip->point = $request->totalPrice;
            $slip->status = 'approve';
            $slip->editby = $admin->firstname . " " . $admin->lastname;
            $slip->save();
            $this->memberService->memberPoint($slip->member, 'plus', $slip->point);
            addMemberHistory($slip->member, 'slip', "approve slip. earn {$slip->point} point.");
            //set data update point
            $topspender = Topspender::firstOrNew(
                ['member' => $slip->member, 'application' => config('app.name')]
            );
            $topspender->point = $topspender->point + $slip->point;
            $topspender->save();
            //create topspender file
            $topspenders = Topspender::select('members.id', 'members.firstname', 'topspenders.point', 'topspenders.application')->
                join('members', 'topspenders.member', '=', 'members.id')
                ->where('topspenders.application', config('app.name'))
                ->orderBy('topspenders.point', 'desc')
                ->orderBy('topspenders.updated_at', 'asc')
                ->limit(10)->get();
            createCustomFile('topspender.json', $topspenders);
        }
        return true;
    }
    public function reject(Request $request)
    {
        $slip = Slip::find($request->slip);
        if ($slip) {
            $admin = Auth::user();
            $slip->status = 'reject';
            $slip->editby = $admin->firstname . " " . $admin->lastname;
            $slip->rejectreason = $request->rejectreason;
            $slip->save();
            addMemberHistory($slip->member, 'slip', 'reject slip.' . $request->rejectreason);
        }
        return true;
    }

    public function list(Request $request)
    {
        //sorting
        $sortColumnIndex = $request->input('order.0.column', 0);
        $sortColumn = $request->input("columns.$sortColumnIndex.name", 'slips.created_at');
        $sortDir = $request->input('order.0.dir', 'asc');

        $search = $request->input('search.value');
        $limit = $request->input('length');
        $start = $request->input('start');

        // prepair query
        $query = Slip::query();
        $query->select(
            'slips.id',
            'slips.receiptno',
            'slips.editby',
            'slips.point',
            'slips.status',
            'slips.created_at',
            'slips.updated_at',
            'members.firstname',
            'members.lastname'
        )->join('members', 'slips.member', '=', 'members.id')
            ->where('slips.application', config('app.name'));

        //total
        $recordsTotal = $query->count();

        $query->whereAny(['slips.editby', 'slips.receiptno', 'members.firstname', 'members.lastname'], 'LIKE', "%{$search}%");

        if ($request->status !== 'all') {
            //filter status
            $query->where('slips.status', $request->status);
        }

        $slips = $query->offset($start)->limit($limit)->orderBy($sortColumn, $sortDir)->get();
        $recordsFiltered = $query->count();

        $result = new \stdClass;
        $result->draw = intval($request->input('draw'));
        $result->recordsTotal = $recordsTotal;
        $result->recordsFiltered = $recordsFiltered;
        $result->data = [];

        foreach ($slips as $slip) {
            $data = new \stdClass;
            $data->id = $slip->id;
            $data->receiptno = $slip->receiptno;
            $data->name = $slip->firstname . " " . $slip->lastname;
            $data->editby = $slip->editby ? $slip->editby : '';
            $data->total = number_format($slip->point, 2, '.', ',');
            $data->btn = 'view';
            switch ($slip->status) {
                case 'process':
                    $data->statustext = '<span class="text-gray">Waiting for verify</span>';
                    $data->edit = route('admin.slip.verify', ['id' => $slip->id]);
                    $data->btn = 'verify';
                    break;
                case 'reject':
                    $data->statustext = '<span class="text-red">Rejected</span>';
                    $data->edit = route('admin.slip.view', ['id' => $slip->id]);
                    break;
                case 'approve':
                    $data->statustext = '<span class="text-green">Approved</span>';
                    $data->edit = route('admin.slip.view', ['id' => $slip->id]);
                    break;
            }
            $data->created_at = date('d/m/Y H:i', strtotime($slip->created_at));
            $data->updated_at = date('d/m/Y H:i', strtotime($slip->updated_at));
            array_push($result->data, $data);
        }
        return $result;
    }
}
