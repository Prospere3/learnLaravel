<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostCreateRequest;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class PostController extends Controller
{
    public function list(Request $request)
    {
        $keyword = $request->get('filters')['keyword'];

        $posts = Post::query()->when($keyword, function ($query) use ($keyword) {
            $query->where('title', 'like', "%$keyword%")
                ->orWhere('description', 'like', "%$keyword%");
        })->get();
        return response()->json($posts);
    }

    public function store(PostCreateRequest $request)
    {
        return Post::query()->create(['title' => $request->get('title'), 'description' => $request->get('description')]);

    }

    public function update(Request $request , $id)
    {
        $post = Post::query()->find($id);
        $post->title = $request->input('title', $post->title);
        $post->description = $request->input('description', $post->description);
        $post->save();
        return response()->json($post);
    }

    public function delete($id)
    {
        return Post::query()->where('id' , $id)->delete();
    }
}
