<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\LikeController;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use DB;
use Validator;


class PostController extends Controller
{
    public function Timeline()
    {
        $allPost = Post::select('posts.id', 'posts.body', 'posts.user_id', 'users.name')
        ->join('users','users.id','=','posts.user_id')
        ->orderBy('posts.id','desc')
        ->paginate();

        return view('posts.timeline', compact('allPost'));
    }

    public function PostCreate()
    {
        return view('posts.postcreate');
    }

    public function PostSave(Request $request)
    {
        $validatedDate = $request->validate([
            'body' => 'required|max:140',
        ],
        [
            'body.required' => '140字以内で投稿して下さい',
        ]);

        $post = new Post();
        $post->user_id = Auth::user()->id;
        $post->body = $request->body;
        $post->save();

        return redirect('timeline')->with('success', '投稿しました');
    }

    public function PostDetail($post_id)
    {
        $post_detail = Post::with('User')->find($post_id);
        return view('posts.detail',compact('post_detail'));
    }

    public function PostDelete($post_id)
    {
        $post_delete = Post::find($post_id);
        $post_delete->delete();
        
        return redirect()->route('posts.timeline')->with('success', '投稿を削除しました');
    }

}
