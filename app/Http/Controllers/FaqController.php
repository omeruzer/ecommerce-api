<?php

namespace App\Http\Controllers;

use App\Models\FAQ;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FaqController extends Controller
{
    public function all()
    {
        $data = FAQ::all();

        return response()->json($data);
    }
    public function index()
    {
        $data = FAQ::paginate(10);

        return response()->json($data);
    }
    public function detail($id)
    {
        $data = FAQ::find($id);

        if (!$data) {
            return response()->json(['data' => 'Question not found!', 'status' => 404], 404);
        }

        return response()->json($data);
    }
    public function create(Request $request)
    {
        $rules = [
            'question' => ['required'],
            'answer' => ['required'],
        ];

        $validator = Validator::make($request->all(), $rules);


        if ($validator->fails()) {
            return response()->json(["status" => 400, "errors" => $validator->errors()], 400);
        } else {
            $data  = FAQ::create([
                'question' => $request->question,
                'answer' => $request->answer,
            ]);

            return response()->json($data);
        }
    }
    public function edit(Request $request, $id)
    {
        $rules = [
            'question' => ['required'],
            'answer' => ['required'],
        ];

        $validator = Validator::make($request->all(), $rules);


        if ($validator->fails()) {
            return response()->json(["status" => 400, "errors" => $validator->errors()], 400);
        } else {

            $data  = FAQ::find($id);

            if (!$data) {
                return response()->json(['data' => 'Question not found!', 'status' => 404], 404);
            }

            $data  = FAQ::where('id', $id)->update([
                'question' => $request->question,
                'answer' => $request->answer,
            ]);

            return response()->json(['data' => 'Updated', 'status' => 200], 200);
        }
    }
    public function remove($id)
    {
        $data = FAQ::find($id);

        if (!$data) {
            return response()->json(['data' => 'Question not found!', 'status' => 404], 404);
        }

        $data->delete();

        return response()->json(['data' => 'Deleted', 'status' => 200]);
    }
}
