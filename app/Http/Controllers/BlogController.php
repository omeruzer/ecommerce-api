<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Str;

class BlogController extends Controller
{
    public function all()
    {
        $blogs = Blog::orderByDesc('id')->paginate(10);

        return response()->json($blogs);
    }
    public function detail($id)
    {
        $blog = Blog::find($id);

        if (!$blog) {
            return response()->json(['data' => 'Blog not found!', 'status' => 404], 404);
        }

        return response()->json($blog);
    }
    public function create(Request $request)
    {
        $rules = [
            'image' => ['required'],
            'title' => ['required'],
            'content' => ['required'],
            'keywords' => ['required'],
            'description' => ['required'],
        ];

        $validator = Validator::make($request->all(), $rules);


        if ($validator->fails()) {
            return response()->json(["status" => 400, "errors" => $validator->errors()], 400);
        } else {
            $image = $request->file('image');

            $fileName = uniqid() . '.' . $image->getClientOriginalExtension();

            Storage::disk('public')->put("media/blogs/" . $fileName, file_get_contents($image));

            $blog = Blog::create([
                'image' => $fileName,
                'title' => $request->title,
                'slug' => Str::slug($request->title),
                'content' => $request->content,
                'keywords' => $request->keywords,
                'description' => $request->description,
            ]);

            return response()->json($blog);
        }
    }
    public function edit(Request $request, $id)
    {
        $rules = [
            'title' => ['required'],
            'content' => ['required'],
            'keywords' => ['required'],
            'description' => ['required'],
        ];

        $validator = Validator::make($request->all(), $rules);


        if ($validator->fails()) {
            return response()->json(["status" => 400, "errors" => $validator->errors()], 400);
        } else {
            $blog = Blog::find($id);

            if (!$blog) {
                return response()->json(['data' => 'Blog not found!', 'status' => 404], 404);
            }

            if ($request->hasFile('image')) {
                $image = $request->file('image');

                $fileName = uniqid() . '.' . $image->getClientOriginalExtension();

                Storage::disk('public')->put("media/blogs/" . $fileName, file_get_contents($image));

                if ($blog->image) {
                    Storage::disk('public')->delete("media/blogs/" . $blog->image);
                }

                $blog->image = $fileName;

                $blog->save();
            }

            $blog->update([
                'title' => $request->title,
                'slug' => Str::slug($request->title),
                'content' => $request->content,
                'keywords' => $request->keywords,
                'description' => $request->description,
            ]);

            return response()->json($blog);
        }
    }
    public function remove($id)
    {
        $blog = Blog::find($id);

        if (!$blog) {
            return response()->json(['data' => 'Blog not found!', 'status' => 404], 404);
        }

        Storage::disk('public')->delete("media/products/" . $blog->image);
        $blog->delete();

        return response()->json(['data' => 'Deleted', 'status' => 200]);
    }
}
