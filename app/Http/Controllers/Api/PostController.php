<?php

namespace App\Http\Controllers\Api;

use App\Models\Post;
use App\Http\Controllers\Controller;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Http\Resources\PostResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{
    public function __construct()
    {
        // $this->middleware('auth:sanctum')->only(['store', 'update', 'destroy']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // get all posts
        $posts = Post::latest()->paginate(10);

        // return the resource
        return (new PostResource(200, "Success get All Post", $posts));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // validate request
        $validation = Validator::make($request->all() ,[
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'title' => 'required|min:5|max:255',
            'content' => 'required',
        ]);

        // if validation fails
        if ($validation->fails())
        {
            return response()->json($request->validator->errors(), 422);
        }
        
        // store image
        $image = $request->file('image');
        $image->storeAs('public/posts', $image->hashName());
        
        // create data
        $data = $validation->validated();
        $data['image'] = $image->hashName();
        
        // create post 
        $post = Post::create($data);

        // return the resource
        return (new PostResource(201, "Success Create Post", $post))
            ->response()
            ->setStatusCode(201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        //
        return (new PostResource(200, "Success get Post", $post))
            ->response()
            ->setStatusCode(200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post)
    {
        if (!auth()->check()){
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        // validate request
        $validation = Validator::make($request->all() ,[
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'title' => 'required|min:5|max:255',
            'content' => 'required',
        ]);

        // if validation fails
        if ($validation->fails())
        {
            dd($request->all());
            return response()->json($validation->errors(), 422);
        }

        // update data
        $data = $validation->validated();

        // if image is not null
        if ($request->hasFile('image')) {

            // delete old image
            Storage::delete('public/posts', basename($post->image));

            // store image
            $image = $request->file('image');
            $image->storeAs('public/posts', $image->hashName());

            // update data
            $data['image'] = $image->hashName();
        } 

        // update post
        $post->update($data);

        return (new PostResource(200, "Data Post Berhasil Diubah", $post))
            ->response()
            ->setStatusCode(200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        if (!auth()->check()){
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        // delete post
        $post->delete();
        return (new PostResource(200, "Data Post Berhasil Dihapus", $post))
            ->response()
            ->setStatusCode(200);
    }
}
