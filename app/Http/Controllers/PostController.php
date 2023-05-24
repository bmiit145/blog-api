<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Post;

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
    $post->save();

    // Return response
    return response()->json(['message' => 'Post created successfully']);
}
}
