<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Post;
use App\Models\Comment;

class Load extends Controller
{
    function get()
    {
        $posts = Http::get("https://jsonplaceholder.typicode.com/posts")->json();
        $comments = Http::get("https://jsonplaceholder.typicode.com/comments")->json();
        
        foreach ($posts as $_post){
            $post = new Post();
            $post->user_id = (int) $_post["userId"];
            $post->title = $_post["title"] ?? "title";
            $post->body = $_post["body"] ?? "body";
            $post->save();
        }

        foreach ($comments as $_comment){
            $comment = new Comment();
            $comment->post_id = (int) $_comment["postId"];
            $comment->email = $_comment["email"] ?? "email";
            $comment->name = $_comment["name"] ?? "name";
            $comment->body = $_comment["body"] ?? "body";
            $comment->save();
        }

        return redirect()->action([Load::class, 'getPost']);
    }

    function getPost(){
        $post = Post::inRandomOrder()->first();
        $comments =$post->comments;
        
        return ["post" => $post, "comments" => $comments];
    }
}
