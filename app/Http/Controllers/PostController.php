<?php
namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\PostResource;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;

class PostController extends Controller
{
    public function store(StorePostRequest $request)
    {
       
        $validatedData = $request->validated();

        $post = Auth::user()->posts()->create($validatedData);

        return response()->json(new PostResource($post), 201);
    }

    public function index()
    {
        $posts = Auth::user()->posts;  

        return PostResource::collection($posts);
    }

    public function show($id)
    {
        $post = Auth::user()->posts()->findOrFail($id);  

        return new PostResource($post);
    }

    public function update(UpdatePostRequest $request, $id)
    {
        $validatedData = $request->validated();

        $post = Auth::user()->posts()->findOrFail($id);

        $post->update($validatedData);

        return response()->json(new PostResource($post));
    }

    public function destroy($id)
    {
        $post = Auth::user()->posts()->findOrFail($id); 

        $post->delete();

        return response()->json(['message' => 'Post deleted successfully']);
    }
}
