<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ContactController extends Controller
{
    public function index()
    {
        $data  = Contact::first();

        return response()->json($data);
    }
    public function create(Request $request)
    {
        $rules = [
            'email' => ['required','email'],
            'phone' => ['required'],
            'address' => ['required'],
        ];

        $validator = Validator::make($request->all(), $rules);


        if ($validator->fails()) {
            return response()->json(["status" => 400, "errors" => $validator->errors()], 400);
        } else {
            $data = Contact::first();
            $data->email=$request->email;
            $data->phone=$request->phone;
            $data->address=$request->address;
            $data->save();

            return response()->json(['data' => 'Updated', 'status' => 200], 200);

        }
    }
}
