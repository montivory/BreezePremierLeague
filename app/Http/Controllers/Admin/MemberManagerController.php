<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Member;
use App\Models\MemberAddress;
use App\Models\MemberTransaction;
use App\Models\Slip;
use App\Models\Topspender;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Storage;
use Str;

class MemberManagerController extends Controller
{

    public function index(Request $request)
    {
        return view('admin.members.index');
    }

    public function topspender(Request $request)
    {
        $topspenders = Topspender::select('members.firstname', 'members.lastname', 'member_addresses.phone', 'topspenders.point')
            ->join(
                'members',
                'topspenders.member',
                '=',
                'members.id'
            )
            ->join(
                'member_addresses',
                'topspenders.member',
                '=',
                'member_addresses.member'
            )
            ->where('topspenders.application', config('app.name'))
            ->where('member_addresses.application', config('app.name'))
            ->orderBy('topspenders.point', 'desc')
            ->orderBy('topspenders.updated_at', 'asc')
            ->limit(10)->get();
        return view('admin.members.topspender', ['topspenders' => $topspenders]);
    }

    public function export(Request $request)
    {
        $members = DB::table('members')
            ->select(
                'members.id',
                'members.firstname',
                'members.lastname',
                'member_accounts.phone',
                'member_accounts.application',
                'member_accounts.authid',
                'member_accounts.created_at'
            )
            ->join('member_accounts', 'members.id', '=', 'member_accounts.member')
            ->where('member_accounts.application', config('app.name'))
            ->orderBy('member_accounts.created_at', 'desc')
            ->get();
        $handle = fopen('php://output', 'w');
        $headerLists = ["id", "firstname", "lastname", "application", "address", "road", "subdistrict", "district", "province", "zipcode", "phone", "authid", "created_at"]; //header
        fputcsv($handle, $headerLists);                                                                                                                                      // Add more headers as needed
        foreach ($members as $member) {
            $address = MemberAddress::where('member', $member->id)->where('application', config('app.name'))->first();
            $data = [];
            array_push($data, $member->id);
            array_push($data, str_replace(",", "", $member->firstname));
            array_push($data, str_replace(",", "", $member->lastname));
            array_push($data, $member->application);
            if ($address) {
                array_push($data, $address->address);
                array_push($data, $address->road);
                array_push($data, $address->subdistrict);
                array_push($data, $address->district);
                array_push($data, $address->province);
                array_push($data, $address->zipcode);
            } else {
                array_push($data, '');
                array_push($data, '');
                array_push($data, '');
                array_push($data, '');
                array_push($data, '');
                array_push($data, '');
            }
            array_push($data, $member->phone);
            array_push($data, $member->authid);
            array_push($data, $member->created_at);
            fputcsv($handle, $data);
        }
        fclose($handle);
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="members.csv"',
        ];
        return Response::make('', 200, $headers);
    }

    public function view($id, Request $request)
    {
        $memberAddress = MemberAddress::where('member', $id)
            ->where('application', config('app.name'))
            ->first();
        if (!$memberAddress) {
            $memberAddress = new MemberAddress;
        }
        $member = DB::table('members')
            ->select(
                'members.id',
                'members.firstname',
                'members.lastname',
                'members.phone',
                'member_points.point'
            )
            ->join('member_points', 'members.id', '=', 'member_points.member')
            ->where('application', config('app.name'))
            ->where('member', $id)
            ->first();
        return view('admin.members.view', ['member' => $member, 'address' => $memberAddress, 'slips' => Slip::where('member', $id)->orderBy('created_at', 'asc')]);
    }

    public function list(Request $request)
    {
        $sortColumnIndex = $request->input('order.0.column', 0);
        $sortColumn = $request->input("columns.$sortColumnIndex.name", 'member_points.point');
        $sortDir = $request->input('order.0.dir', 'asc');

        $search = $request->input('search.value');
        $limit = $request->input('length');
        $start = $request->input('start');

        // prepair query
        $query = Member::query();

        $query->select(
            'members.id',
            'members.firstname',
            'members.lastname',
            'members.phone',
            'member_points.point'
        )
            ->join('member_points', 'members.id', '=', 'member_points.member')
            ->where('member_points.application', config('app.name'))->count();

        $recordsTotal = $query->count();

        $query->whereAny(['members.firstname', 'members.lastname', 'members.phone'], 'LIKE', "%{$search}%");

        $members = $query->offset($start)->limit($limit)->orderBy($sortColumn, $sortDir)->get();
        $recordsFiltered = $query->count();

        $result = new \stdClass;
        $result->draw = intval($request->input('draw'));
        $result->recordsTotal = $recordsTotal;
        $result->recordsFiltered = $recordsFiltered;
        $result->data = [];

        foreach ($members as $member) {
            $data = new \stdClass;
            $data->id = $member->id;
            $data->fullname = $member->firstname . " " . $member->lastname;
            $data->phone = $member->phone;
            $data->point = number_format($member->point, 2, '.', ',');
            $data->type = '';
            $data->view = route('admin.member.view', ['id' => $member->id]);
            array_push($result->data, $data);
        }
        return $result;
    }

    public function listslip($id, Request $request)
    {
        $sortColumnIndex = $request->input('order.0.column', 0);
        $sortColumn = $request->input("columns.$sortColumnIndex.name", 'created_at');
        $sortDir = $request->input('order.0.dir', 'asc');

        $search = $request->input('search.value');
        $limit = $request->input('length');
        $start = $request->input('start');

        $query = Slip::query();
        $query->where('application', config('app.name'));

        $recordsTotal = $query->count();

        $query->where('member', $id)->whereAny(['receiptno'], 'LIKE', "%{$search}%");
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
            $data->total = number_format($slip->point, 2, '.', ',');
            switch ($slip->status) {
                case 'process':
                    $data->statustext = '<span class="text-gray">Waiting for verify</span>';
                    break;
                case 'reject':
                    $data->statustext = '<span class="text-red">Rejected</span>';
                    break;
                case 'approve':
                    $data->statustext = '<span class="text-green">Approved</span>';
                    break;
            }
            $data->created_at = date_format($slip->created_at, 'd/m/y H:i');
            array_push($result->data, $data);
        }
        return $result;
    }

    public function upload($id, Request $request)
    {
        $slipId = Str::uuid();
        $uploadedFile = $request->file('slip');
        $extension = $uploadedFile->getClientOriginalExtension();
        $filename = $slipId . '.' . $extension;
        if (!Storage::disk('azure')->putFileAs(config('app.azurepath'), $uploadedFile, $filename)) {
            return ["result" => false];
        }
        $url = Storage::disk('azure')->url(config('app.azurepath') . '/' . $filename);
        $slip = new Slip;
        $slip->id = $slipId;
        $slip->receiptno = '-';
        $slip->merchantname = '-';
        $slip->total = 0;
        $slip->point = 0;
        $slip->status = 'process';
        $slip->application = config('app.name');
        $slip->member = $id;
        $slip->slipname = $filename;
        $slip->url = $url;
        $slip->system = true;
        $slip->save();
        //add transaction
        $memberTransaction = new MemberTransaction;
        $memberTransaction->member = $id;
        $memberTransaction->slip = $slip->id;
        $memberTransaction->application = config('app.name');
        $memberTransaction->save();
        return ['result' => true];
    }
}
