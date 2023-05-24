<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Post;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    // create new post
    public function create(Request $request)
    {
        // Validate input
        $validatedData = $request->validate([
            'title' => 'required|string',
            'description' => 'required|string',
            'image' => 'required|image',
            'thumbnail' => 'required|image',
        ]);

        // Save images
        $imagePath = $request->file('image')->store('public/images');
        $thumbnailPath = $request->file('thumbnail')->store('public/thumbnails');

        // Create new post
        $post = new Post();
        $post->title = $validatedData['title'];
        $post->description = $validatedData['description'];
        $post->image = $imagePath;
        $post->thumbnail = $thumbnailPath;
        $post -> user_id = Auth::id();
        $post->save();

        // Return response
        return response()->json(['message' => 'Post created successfully']);
    }

    // update the post
    public function update(Request $request, $postId)
    {
        // dd($request->all());
        // Validate input
        $validatedData = $request->validate([
            'title' => 'required|string',
            'description' => 'required|string',
            'image' => 'image',
            'thumbnail' => 'image',
        ]);

        // Find post by ID
        $post = Post::find($postId);

        // Update post data
        $post->title = $validatedData['title'];
        $post->description = $validatedData['description'];

        // Update images if provided
        if ($request->hasFile('image')) {
            Storage::delete($post->image);
            $post->image = $request->file('image')->store('public/images');
        }
        if ($request->hasFile('thumbnail')) {
            Storage::delete($post->thumbnail);
            $post->thumbnail = $request->file('thumbnail')->store('public/thumbnails');
        }

        $post->save();

        // Return response
        return response()->json(['message' => 'Post updated successfully']);
    }

    // delete a post by post ID
    public function delete($postId)
    {
        // Find post by ID
        $post = Post::find($postId);

        // Delete images
        Storage::delete($post->image);
        Storage::delete($post->thumbnail);

        // Delete post
        $post->delete();

        // Return response
        return response()->json(['message' => 'Post deleted successfully']);
    }


    // toggle active or inactive
    public function toggleActive($postId)
    {
        // Find post by ID
        $post = Post::find($postId);

        // Toggle active status
        $post->active = !$post->active;
        $post->save();

        // Return response
        return response()->json(['message' => 'Post status updated successfully']);
    }

    // get all post
    public function index()
    {
        // Get all posts of the logged-in user
        // dd(Auth::user());
        $posts = Post::where('user_id', Auth::id())->get();

        // Return response with posts
        return response()->json($posts);
    }

    // get one post
    public function show($postId)
    {
        // Find post by ID
        $post = Post::find($postId);

        // Return response with post details
        return response()->json($post);
    }
}