<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $rules = [
            'email' => ['required', 'email'],
            'password' => ['required'],
        ];

        $validator = Validator::make($request->all(), $rules);


        if ($validator->fails()) {
            return response()->json(["status" => 400, "errors" => $validator->errors()], 400);
        } else {
            $data = [
                'email' =>  $request->email,
                'password' => $request->password,
            ];

            if (Auth::attempt($data)) {
                $token = auth()->user()->createToken('myapp')->plainTextToken;
                return response()->json(['user' => $request->email, 'token' => $token]);
            } else {
                return response()->json(['message' => 'Email or password is incorrect']);
            }
        }
    }

    public function register(Request $request)
    {
        $rules = [
            'name' => ['required'],
            'email' => ['required', 'email', 'unique:users'],
            'password' => [
                'required',
                'string',
                'min:10',             // En az 10 karakter
                'regex:/[a-z]/',      // en az bir adet a-z
                'regex:/[A-Z]/',      // en az bir adet A-Z
                'regex:/[0-9]/',      // en az bir adet 0-9
                'regex:/[@$!%*#?&]/', // en az bir adet özel karakter,
                'confirmed'           // Konfirme edilmiş olmalı
            ],
        ];

        $validator = Validator::make($request->all(), $rules);


        if ($validator->fails()) {
            return response()->json(["status" => 400, "errors" => $validator->errors()], 400);
        } else {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            UserInfo::create([
                'user_id' => $user->id
            ]);

            return response()->json(['status' => 200, 'message' => 'Success', 'user' => $user]);
        }
    }

    public function user()
    {
        $user = User::with('info')->find(Auth::user());
        return response()->json($user);
    }

    public function logout(Request $request)
    {
        auth()->guard('web')->logout();

        return response()->json('logout');
    }

    public function authUserUpdate(Request $request)
    {
        $rules = [
            'name' => ['required'],
            'email' => ['email', 'unique:users'],
        ];

        $validator = Validator::make($request->all(), $rules);


        if ($validator->fails()) {
            return response()->json(["status" => 400, "errors" => $validator->errors()], 400);
        } else {
            $data = [
                'name' => $request->name,
            ];

            if ($request->email) {
                $data['email'] = $request->email;
            }

            $userInfo = UserInfo::where('user_id', Auth::id())->first();
            if ($request->address) {
                $userInfo->address = $request->address;
                $userInfo->save();
            }

            if ($request->postal_code) {
                $userInfo->postal_code = $request->postal_code;
                $userInfo->save();
            }
            if ($request->city) {
                $userInfo->city = $request->city;
                $userInfo->save();
            }
            if ($request->country) {
                $userInfo->country = $request->country;
                $userInfo->save();
            }
            if ($request->phone) {
                $userInfo->phone = $request->phone;
                $userInfo->save();
            }

            $user = User::where('id', Auth::id())->update($data);

            return response()->json(['status' => true, 'message' => 'Updated']);
        }
    }

    public function userPass(Request $request)
    {
        $rules = [
            'old_password' => ['required'],
            'new_password' => [
                'required',
                'string',
                'min:10',             // En az 10 karakter
                'regex:/[a-z]/',      // en az bir adet a-z
                'regex:/[A-Z]/',      // en az bir adet A-Z
                'regex:/[0-9]/',      // en az bir adet 0-9
                'regex:/[@$!%*#?&]/', // en az bir adet özel karakter,
                'confirmed'           // Konfirme edilmiş olmalı
            ],
        ];

        $validator = Validator::make($request->all(), $rules);


        if ($validator->fails()) {
            return response()->json(["status" => 400, "errors" => $validator->errors()], 400);
        } else {
            $user = User::where('id', Auth::id())->first();
            $userPass = $user->password;
            $pass = $request->old_password;
            if (Hash::check($pass, $userPass)) {
                $user->update([
                    'password' => Hash::make($request->new_password)
                ]);
                return response()->json(['status' => true, 'msg' => 'Updated']);
            } else {
                return response()->json(['status' => false, 'msg' => 'passwords do not match']);
            }
        }
    }
}
