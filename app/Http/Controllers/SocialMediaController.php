<?php

namespace App\Http\Controllers;

use App\Models\SocialMedia;
use Illuminate\Http\Request;

class SocialMediaController extends Controller
{
    public function index()
    {
        $data = SocialMedia::first();

        return response()->json($data);
    }
    public function create(Request $request)
    {
        $data = [];

        if ($request->whatsapp) {
            $data['whatsapp'] = $request->whatsapp;
        }
        if ($request->instagram) {
            $data['instagram'] = $request->instagram;
        }
        if ($request->facebook) {
            $data['facebook'] = $request->facebook;
        }
        if ($request->telegram) {
            $data['telegram'] = $request->telegram;
        }
        if ($request->youtube) {
            $data['youtube'] = $request->youtube;
        }
        if ($request->twitter) {
            $data['twitter'] = $request->twitter;
        }

        SocialMedia::updateOrCreate(['id' => 1], $data);
    }
}
