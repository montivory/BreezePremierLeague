<?php

namespace App\Http\Controllers;

use App\Models\MemberAccount;
use Illuminate\Http\Request;

class VerifyController extends Controller
{
    public function checkEmail(Request $request)
    {
        $count = MemberAccount::where('email', $request->email)
            ->where('application', config('app.name'))
            ->where('type', 'password')
            ->count();
        if ($count > 0) {
            return ['result' => true];
        }
        return ['result' => false];
    }

    public function checkPhone(Request $request)
    {
        $phone = filterChar($request->phone, 'phone');
        $count = MemberAccount::where('phone', $phone)
            ->where('application', config('app.name'))
            ->where('type', 'password')
            ->count();
        if ($count > 0) {
            return ['result' => true];
        }
        return ['result' => false];
    }
}
