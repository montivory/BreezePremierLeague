<?php
namespace App\Services;

use App\Models\MemberAddress;
use App\Models\MemberPoint;

class MemberService
{
    public function memberAddress($member, $request)
    {
        $address = MemberAddress::firstOrNew(['member' => $member->id, 'application' => config('app.name')]);
        $address->member = $member->id;
        $address->address = $request->address;
        $address->road = $request->road;
        $address->province = $request->province;
        $address->district = $request->district;
        $address->subdistrict = $request->subdistrict;
        $address->zipcode = $request->postcode;
        $address->application = config('app.name');
        $address->phone = $request->phone;
        $address->save();
    }

    public function memberPoint($memberId, $event, $point = 0)
    {
        $memberPoint = MemberPoint::firstOrNew(['member' => $memberId, 'application' => config('app.name')]);
        switch ($event) {
            case 'plus':
                $memberPoint->point = $memberPoint->point + floatval($point);
                break;
            case 'minus':
                $memberPoint->point = $memberPoint->point - floatval($point);
                break;
            default:
                $memberPoint->point = $point;
                break;
        }
        $memberPoint->save();
        return $memberPoint->point;
    }

    public function getTopSpender()
    {
        return readCustomFile('topspender.json');
    }
}
