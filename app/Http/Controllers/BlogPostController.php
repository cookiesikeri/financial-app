<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use Illuminate\Http\Request;
use App\Traits\ManagesResponse;
use Exception;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Tymon\JWTAuth\Facades\JWTAuth;

class BlogPostController extends Controller
{
    use ManagesResponse;
    protected $utility;
    protected $jwt;

    function __construct(JWTAuth $jwt)
    {
        $this->jwt = $jwt;
        $this->utility = new Functions();
    }

    public function index()
    {
        return $this->sendResponse(BlogPost::latest(), 'All blog posts');
    }

    public function store(Request $request)
    {
        try{
            $validator = Validator::make($request->all(), [
                'title'=>'required|string',
                'content'=>'required|string',
                'description'=>'required|string',
                'image'=>'required|file',
                'author'=>'required|string',
            ]);

            if ($validator->fails()) {
                return response()->json(['errors'=>$validator->errors()], 400);
            }

            $slug = Str::slug($request->title);
            $imageUrl = $this->utility->saveFile($request->file('image'), 'blog/posts', $slug);

            $post = BlogPost::create([
                'title'=>$request->title,
                'content'=>$request->content,
                'short_desc'=>$request->description,
                'image_url'=>$imageUrl,
                'author'=>$request->author,
                'slug'=>$slug,
            ]);

            return $this->sendResponse($post, 'Post stored successfully');
        }
        catch(Exception $e)
        {
            return $this->sendError($e->getMessage());
        }
    }

    public function update(request $request, $slug)
    {
        $validator = Validator::make($request->all(), [
            'title'=>'required|string',
            'content'=>'required|string',
            'description'=>'required|string',
            'image'=>'required|file',
            'author'=>'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors'=>$validator->errors()], 400);
        }

        $post = BlogPost::where('slug', $slug)->first();
        $imageUrl = $this->utility->saveFile($request->file('image'), 'blog/posts', $slug);
        $post->update([
            'title'=>$request->title,
            'content'=>$request->content,
            'short_desc'=>$request->description,
            'image_url'=>$imageUrl,
            'author'=>$request->author,
            'slug'=>$slug,
        ]);

        return $this->sendResponse($post, 'Post updated successfully');
    }

    public function destroy($slug)
    {
        $post = BlogPost::where('slug', $slug)->first();

        $post->delete();

        return $this->sendResponse($post, 'Post deleted successfully');
    }

    public function show($slug)
    {
        $post = BlogPost::where('slug', $slug)->first();

        return $this->sendResponse($post, 'Post retrieved successfully');
    }
}
