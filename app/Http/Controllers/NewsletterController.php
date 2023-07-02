<?php

namespace App\Http\Controllers;

use App\Mail\NewsletterMail;
use App\Models\Newsletter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class NewsletterController extends Controller
{
    public function index()
    {
        $emails = Newsletter::paginate(10);

        return response()->json($emails);
    }
    public function create(Request $request)
    {
        $rules = [
            'email' => ['required', 'email'],
        ];

        $validator = Validator::make($request->all(), $rules);


        if ($validator->fails()) {
            return response()->json(["status" => 400, "errors" => $validator->errors()], 400);
        } else {
            $newsletter = Newsletter::create(['email' => $request->email]);

            return response()->json($newsletter);
        }
    }

    public function remove($id)
    {
        $newsletter = Newsletter::find($id);

        if (!$newsletter) {
            return response()->json(["status" => 404, "data" => 'Email not found'], 404);
        }

        $newsletter->delete();

        return response()->json(["status" => 200, "data" => 'Deleted'], 200);
    }

    public function mail(Request $request)
    {
        $rules = [
            'title' => ['required'],
            'content' => ['required'],
        ];

        $validator = Validator::make($request->all(), $rules);


        if ($validator->fails()) {
            return response()->json(["status" => 400, "errors" => $validator->errors()], 400);
        } else {
            $data = [
                'title' => $request->title,
                'content' => $request->content,
            ];

            $users = Newsletter::all();

            foreach ($users as $key => $user) {
                Mail::to($user['email'])->send(new NewsletterMail($data));
            }

            return response()->json(['status' => 200, 'data' => 'Send Mail'], 200);
        }
    }
}
