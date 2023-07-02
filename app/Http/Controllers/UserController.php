<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function index()
    {
        $users = User::paginate(10);

        return response()->json($users);
    }
    public function detail($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['status' => 404, 'data' => 'User not found'], 404);
        }

        return response()->json($user);
    }
    public function create(Request $request)
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

            $userInfo = UserInfo::create([
                'user_id' => $user->id
            ]);

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

            return response()->json(['status' => 200, 'message' => 'Success', 'user' => $user]);
        }
    }
    public function edit(Request $request, $id)
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

            $userInfo = UserInfo::where('user_id', $id)->first();
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

            $user = User::where('id', $id)->update($data);

            return response()->json(['status' => true, 'message' => 'Updated']);
        }
    }
    public function remove($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['status' => 404, 'data' => 'User not found'], 404);
        }

        $user->delete();

        return response()->json(['status' => 200, 'data' => 'Deleted'], 200);
    }
    public function permission(Request $request)
    {
        $user = User::find($request->user_id);

        if (!$user) {
            return response()->json(['status' => 404, 'data' => 'User not found'], 404);
        }

        $user->is_admin = !$user->is_admin;
        $user->save();

        return response()->json(['status' => 200, 'data' => 'Updated'], 200);
    }
}
