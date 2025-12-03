<?php
use App\Models\Member;
use App\Models\MemberAccount;
use App\Models\MemberHistory;
use App\Models\MemberPoint;
use App\Models\Reward;
use App\Models\Slip;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Carbon\Carbon;

if (!function_exists('createReward')) {
    function createReward($nameth, $nameen, $slug, $point, $quantity, $order, $thumbnail, $banner, $lucky, $startdate, $enddate)
    {
        $des = "
        Lorem ipsum dolor  sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt  ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud  exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.  Duis aute irure dolor in reprehenderit in voluptate velit esse cillum  dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non  proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
        ";
        $count = Reward::where('slug', $slug)
            ->where('application', config('app.name'))
            ->where('startdate', $startdate)
            ->where('enddate', $enddate)
            ->count();
        if ($count == 0) {
            $reward = new Reward;
            $reward->banner = $banner;
            $reward->thumbnail = $thumbnail;
            $reward->nameen = $nameen;
            $reward->nameth = $nameth;
            $reward->slug = $slug;
            $reward->descriptionen = $des;
            $reward->descriptionth = $des;
            $reward->conditionen = $des;
            $reward->conditionth = $des;
            $reward->point = $point;
            $reward->quantity = $quantity;
            $reward->amount = $quantity;
            $reward->ordernumber = $order;
            $reward->application = config('app.name');
            $reward->lucky = $lucky;
            $reward->startdate = $startdate;
            $reward->enddate = $enddate;
            $reward->save();
        }
    }
}

if (!function_exists('lineSignin')) {
    function lineSignin($lineData)
    {
        $account = MemberAccount::where('authid', $lineData->sub)
            ->where('type', 'line')
            ->where('application', config('app.name'))
            ->first();
        //not found account
        if (!$account) {
            Session::put('tempmember', $lineData);
            return false;
        }
        //find member data
        $member = Member::find($account->member);
        $member->point = 0;
        $memberPoint = MemberPoint::where('member', $member->id)
            ->where('application', config('app.name'))
            ->first();
        if ($memberPoint) {
            $member->point = $memberPoint->point;
        }
        $member->phone = $account->phone;
        Session::put('member', $member);
        addMemberHistory($member->id, 'member', 'signin via line');
        return true;
    }
}

if (!function_exists('lineRegister')) {
    function lineRegister($lineData)
    {
        $account = Account::where('authid', $lineData->sub)
            ->where('type', 'line')
            ->where('application', 'sunsilkmissgrand')
            ->get()->first();
        $result = new \stdClass;
        if (!$account) {
            $result->result = false;
            session(['tempmember' => $lineData]);
            return $result;
        }
        $result->result = true;
        $member = new Member;
        if (!$account) {
            $member->id = Str::uuid();
            $member->firstname = $lineData->name;
            if (isset($lineData->phone_number)) {
                $member->phone = $lineData->phone_number;
            }
            if (isset($lineData->email)) {
                $member->email = $lineData->email;
            }
            $member->save();
            $account = new Account;
            $account->id = Str::uuid();
            $account->type = 'line';
            $account->application = 'clear';
            $account->authid = $lineData->sub;
            $account->member = $member->id;
            $account->verify = true;
            $account->save();
        } else {
            $member = Member::find($account->member);
        }
        session(['member' => $member]);
        addMemberHistory($member->id, 'member', 'signup new member');
        addMemberHistory($member->id, 'member', 'signin via line');
    }
}

if (!function_exists('addMemberHistory')) {
    function addMemberHistory($mid, $type, $detail)
    {
        $memberHistory = new MemberHistory;
        $memberHistory->member = $mid;
        $memberHistory->type = $type;
        $memberHistory->detail = $detail;
        $memberHistory->application = config('app.name');
        $memberHistory->save();
    }
}

if (!function_exists('filterChar')) {
    function filterChar($string, $type = 'name')
    {
        switch ($type) {
            case 'url':
                $string = str_replace(' ', '-', $string);
                $string = str_replace('/', '-', $string);
                $string = preg_replace('/[^a-z0-9-\/:.]/i', '', $string);
                // $string = strtolower($string);
                break;
            case 'phone':
                $string = str_replace('+66', '0', $string);
                $string = preg_replace('/[^0-9]/i', '', $string);
                break;
            case 'file':
                $string = preg_replace('/[^a-z0-9]/i', '', $string);
                if ($string == '') {
                    $string = 'file';
                }
                break;
            default:
                $string = preg_replace('/[^a-z0-9]/i', '', $string);
                $string = strtolower($string);
                break;
        }
        return $string;
    }
}

if (!function_exists('renamePhoto')) {
    function renamePhoto($fileimage)
    {
        $filenameElements = explode('.', $fileimage);
        $extension = array_pop($filenameElements);
        return filterChar($filenameElements[0], 'file') . "-" . Str::uuid() . "." . $extension;
    }
}

if (!function_exists('countSlips')) {
    function countSlips($status)
    {
        if ($status == 'all') {
            return number_format(Slip::where('application', config('app.name'))->count(), 0, '');
        }
        return number_format(Slip::where('application', config('app.name'))->where('status', $status)->count(), 0, '');
    }
}

if (!function_exists('countMembers')) {
    function countMembers()
    {
        return number_format(DB::table('member_accounts')
            ->select('member_accounts.member')
            ->join('members', 'member_accounts.member', '=', 'members.id')
            ->where('member_accounts.application', config('app.name'))->count(), 0, '');
    }
}

if (!function_exists('createGigyaData')) {
    function createGigyaData($request)
    {
        $gigya = new \stdClass;
        $gigya->profile = new \stdClass;
        $gigya->profile->header = new \stdClass;
        $gigya->profile->header->isoLanguage = "EN";
        $gigya->profile->header->isoCountry = "TH";
        $gigya->profile->header->brandCode = config('app.gigya_brandCode');
        $gigya->profile->header->campaignId = config('app.gigya_campaignId');
        $gigya->profile->header->campaignDescription = "Always On";
        $gigya->profile->header->origin = "https://www.unileverprokhum.com";
        $gigya->profile->header->formType = "signUp";
        $gigya->profile->header->entity = "PRM2.6";
        $gigya->profile->consumerIdentity = new \stdClass;
        $gigya->profile->consumerIdentity->firstname = $request->firstname;
        $gigya->profile->consumerIdentity->lastName = '';
        $gigya->profile->contactDetail = new \stdClass;
        $gigya->profile->contactDetail->mobileNumber = "+66" . substr($request->phone, 1, 9);
        $gigya->profile->optInStatus = new \stdClass;
        $gigya->profile->optInStatus->unileverSMSConsent = true;
        $gigya->profile->optInStatus->legalAgeConsent = true;
        //QA id
        $gigya->profile->questionAndAnswers = [];
        $qObj = new \stdClass;
        $qObj->questionId = 3206;
        $qObj->questionText = "What campaign does the data fall under?";
        $qObj->answerId = [-1];
        $qObj->answerText = 'Breeze Premier League'; //pending
        array_push($gigya->profile->questionAndAnswers, $qObj);
        // $gigya->result = new \stdClass;
        // $gigya->unileverId = '';
        try {
            if (config('app.env') != 'local') {
                $curl = curl_init();
                curl_setopt_array($curl, array(
                    CURLOPT_URL => config('app.gigya_server'),
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => '',
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => 'POST',
                    CURLOPT_POSTFIELDS => json_encode($gigya, JSON_UNESCAPED_UNICODE),
                    CURLOPT_HTTPHEADER => array(
                        "x-api-key: " . config('app.gigya_token'),
                        'Content-Type: application/json',
                    ),
                    CURLOPT_PROXY => 'http://10.102.4.5:3128',
                    CURLOPT_NOPROXY => 'localhost,127.0.0.1,10.55.6.4,10.54.7.5,*.blob.core.windows.net',
                ));

                $response = curl_exec($curl);
                curl_close($curl);
                $response = json_decode($response, TRUE);
                // $gigya->result = new \stdClass;
                $gigya->unileverId = $response['UnileverId'];
                // send to gigya
                // $response = Http::withBody(json_encode($gigya), 'application/json')
                //     ->withHeaders([
                //         'x-api-key' => config('app.gigya_token'),
                //         'Content-Type' => 'application/json',
                //     ])
                //     ->post(config('app.gigya_server'));
                // $gigyaresult = $response->object();
                // $gigya->result = $gigyaresult;
                // if (isset($gigyaresult->UnileverId)) {
                //     $gigya->unileverId = $response->json('UnileverId');
                // }
            }
        } catch (\Exception $e) {
            return $gigya;
        }
        if (isset($gigya->unileverId) && $gigya->unileverId != '') {
            setcookie("UnileverId", $gigya->unileverId, time() + 30 * 24 * 60 * 60, "/", "", false, false);
        }
        return 'success';
    }
}

if (!function_exists('sendMerchantNameGigya')) {
    function sendMerchantNameGigya($request): void
    {
        $member = session('member');
        $gigya = new \stdClass;
        $gigya->profile = new \stdClass;
        $gigya->profile->header = new \stdClass;
        $gigya->profile->header->isoLanguage = "EN";
        $gigya->profile->header->isoCountry = "TH";
        $gigya->profile->header->brandCode = config('app.gigya_brandCode');
        $gigya->profile->header->campaignId = config('app.gigya_campaignId');
        $gigya->profile->header->campaignDescription = "Always On";
        $gigya->profile->header->origin = "https://www.unileverprokhum.com";
        $gigya->profile->header->formType = "signUp";
        $gigya->profile->header->entity = "PRM2.6";
        $gigya->profile->consumerIdentity = new \stdClass;
        $gigya->profile->consumerIdentity->firstname = $member->firstname;
        $gigya->profile->consumerIdentity->lastName = $member->lastname;
        $gigya->profile->contactDetail = new \stdClass;
        $gigya->profile->contactDetail->mobileNumber = "+66" . substr($member->phone, 1, 9);
        $gigya->profile->optInStatus = new \stdClass;
        $gigya->profile->optInStatus->unileverSMSConsent = true;
        $gigya->profile->optInStatus->legalAgeConsent = true;
        //QA id
        $ansArrays = [];
        $qObj = new \stdClass;
        $qObj->questionId = 3206;
        $qObj->questionText = "What campaign does the data fall under?";
        $qObj->answerId = [-1];
        $qObj->answerText = 'Breeze Premier League'; //pending
        array_push($ansArrays, $qObj);
        $nansArrays = qnada($ansArrays, $request);
        $gigya->profile->questionAndAnswers = $nansArrays;
        // $gigya->result = new \stdClass;
        // $gigya->unileverId = '';
        try {
            if (config('app.env') != 'local') {
                $curl = curl_init();
                curl_setopt_array($curl, array(
                    CURLOPT_URL => config('app.gigya_server'),
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => '',
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => 'POST',
                    CURLOPT_POSTFIELDS => json_encode($gigya, JSON_UNESCAPED_UNICODE),
                    CURLOPT_HTTPHEADER => array(
                        "x-api-key: " . config('app.gigya_token'),
                        'Content-Type: application/json',
                    ),
                    // CURLOPT_PROXY => 'http://10.102.4.5:3128',
                    // CURLOPT_NOPROXY => 'localhost,127.0.0.1,10.55.6.4,10.54.7.5,*.blob.core.windows.net',
                ));

                $response = curl_exec($curl);
                curl_close($curl);
                if (config('app.debug')) {
                    Log::info('Gigya Data: ' . $response);
                }
                // $response = json_decode($response, true);
                // $gigya->result = new \stdClass;
                // $gigya->unileverId = $response['UnileverId'];
                // send to gigya
                // $response = Http::withBody(json_encode($gigya), 'application/json')
                //     ->withHeaders([
                //         'x-api-key' => config('app.gigya_token'),
                //         'Content-Type' => 'application/json',
                //     ])
                //     ->post(config('app.gigya_server'));
                // $gigyaresult = $response->object();
                // $gigya->result = $gigyaresult;
                // if (isset($gigyaresult->UnileverId)) {
                //     $gigya->unileverId = $response->json('UnileverId');
                // }
            }
        } catch (\Exception $e) {
        }
    }
}

if (!function_exists('qanda')) {
    function qnada($ansArrs, $request)
    {
        // merchant_name
        $q1s = $request->merchant_name;
        $q1id = "";
        $q1t = "";
        switch ($q1s) {
            case 1263:
                $q1id = 1263;
                $q1t = "E-commerce - Shopee";
                break;
            case 1262:
                $q1id = 1262;
                $q1t = "E-commerce - Lazada";
                break;
            case 3657:
                $q1id = 3657;
                $q1t = "App - TikTok";
                break;
        }
        $qObj = new \stdClass;
        $qObj->questionId = 1346;
        $qObj->questionText = "What is your shopping channel/retailer?";
        $qObj->answerId = $q1id;
        $qObj->answerText = $q1t;
        array_push($ansArrs, $qObj);
        return $ansArrs;
    }
}

if (!function_exists('createDate')) {
    function createDate($dateString = '', $timeZone = '')
    {
        $convertTimezone = new DateTimeZone(config('app.timezone'));
        if ($timeZone != '') {
            $convertTimezone = new DateTimeZone($timeZone);
        }
        if ($dateString == '') {
            return new DateTime('now', $convertTimezone);
        } else {
            $dateTimeObj = new DateTime();
            return $dateTimeObj->createFromFormat('Y-m-d H:i:s', $dateString, $convertTimezone);
        }
    }
}

if (!function_exists('createCustomFile')) {
    function createCustomFile($filename, $data)
    {
        Storage::disk('local')->put($filename, json_encode($data, JSON_UNESCAPED_UNICODE));
    }
}

if (!function_exists('readCustomFile')) {
    function readCustomFile($filename)
    {
        if (Storage::disk('local')->exists($filename)) {
            return json_decode(Storage::disk('local')->get($filename));
        }
        return null;
    }
}

if (!function_exists('checkTimeDiff')) {
    function checkTimeDiff($calculateTime)
    {
        $useTime = Carbon::createFromFormat('Y-m-d H:i:s', $calculateTime, 'Asia/Bangkok');
        // เวลาปัจจุบันใน timezone เดียวกัน
        $now = Carbon::now('Asia/Bangkok');
        // คำนวณความต่างเป็นนาที
        $diffInSeconds = $useTime->diffInSeconds($now, false); // false เพื่อให้ได้ค่าลบถ้าอยู่ในอดีต
        if ($diffInSeconds > 1800) {
            $remainingSeconds = 0;
        } else {
            $remainingSeconds = 1800 - $diffInSeconds;
        }
        return $remainingSeconds;
    }
}
