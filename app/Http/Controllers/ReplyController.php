<?php

namespace App\Http\Controllers;

use App\Reply;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReplyController extends Controller
{
    public function replyComment(Request $request, $id)
    {
        $replies = new Reply();
        $replies->body = $request->input('body');
        $replies->user_id = Auth::user()->id;
        $replies->post_id = $request->post_id;
        $replies->comment_id =  $id;
        $replies->save();
        return redirect()->route('post.show', $request->post_id );
    }

}
