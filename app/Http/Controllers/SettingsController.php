<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class SettingsController extends Controller
{
    public function index()
    {
        $data = Setting::first();

        return response()->json($data);
    }
    public function edit(Request $request)
    {
        $rules = [
            'title' => ['required'],
            'description' => ['required'],
            'keywords' => ['required'],
            'author' => ['required'],
        ];

        $validator = Validator::make($request->all(), $rules);


        if ($validator->fails()) {
            return response()->json(["status" => 400, "errors" => $validator->errors()], 400);
        } else {
            $settings = Setting::first();

            $settings->title = $request->title;
            $settings->description = $request->description;
            $settings->keywords = $request->keywords;
            $settings->author = $request->author;
            $settings->save();

            return response()->json(["status" => 200, "data" => 'Updated'], 200);
        }
    }

    public function logo(Request $request)
    {
        $rules = [
            'logo' => ['required', 'file'],
        ];

        $validator = Validator::make($request->all(), $rules);


        if ($validator->fails()) {
            return response()->json(["status" => 400, "errors" => $validator->errors()], 400);
        } else {
            $settings =  Setting::first();
            $logo = $request->file('logo');

            $fileName = uniqid() . '.' . $logo->getClientOriginalExtension();

            Storage::disk('public')->put("media/logo/" . $fileName, file_get_contents($logo));

            if ($settings->logo) {
                Storage::disk('public')->delete("media/logo/" . $settings->logo);
            }

            $settings->logo = $fileName;

            $settings->save();

            return response()->json(["status" => 200, "data" => 'Updated'], 200);
        }
    }
    public function favicon(Request $request)
    {
        $rules = [
            'favicon' => ['required', 'file'],
        ];

        $validator = Validator::make($request->all(), $rules);


        if ($validator->fails()) {
            return response()->json(["status" => 400, "errors" => $validator->errors()], 400);
        } else {
            $settings =  Setting::first();
            $favicon = $request->file('favicon');

            $fileName = uniqid() . '.' . $favicon->getClientOriginalExtension();

            Storage::disk('public')->put("media/logo/" . $fileName, file_get_contents($favicon));

            if ($settings->favicon) {
                Storage::disk('public')->delete("media/logo/" . $settings->favicon);
            }

            $settings->favicon = $fileName;

            $settings->save();

            return response()->json(["status" => 200, "data" => 'Updated'], 200);
        }
    }
}
