<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    public function profile(Request $request)
    {
        $id = Auth::id();
        $data = User::find($id);
        return view('admin.user.profile', ['data' => $data]);
    }

    public function store(Request $request)
    {
        $validatorRule = [];
        $validatorRule['firstname'] = ['required', 'string'];
        $validatorRule['lastname'] = ['required', 'string'];
        if (!empty($request->password)) {
            $validatorRule['password'] = ['required', 'confirmed', Password::min(8)->mixedCase()->letters()->numbers()];
        }
        $validator = Validator::make($request->all(), $validatorRule);
        if ($validator->fails()) {
            return response()->json([
                'result' => false,
                'messages' => $validator->errors(),
            ], 400);
        }
        $user = User::find($request->id);
        if (!empty($request->password)) {
            $user->password = Hash::make($request->password);
        }
        $user->firstname = $request->firstname;
        $user->lastname = $request->lastname;
        if (!$user->save()) {
            return ['result' => false];
        }
        return ['result' => true];
    }

}
