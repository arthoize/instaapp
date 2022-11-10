<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CommentController extends Controller
{
    public function save(Request $request, $postId)
    {
        $validator = Validator::make(
            $datas = [
                'id' => $postId,
                'comment' => $request->comment
            ],
            $rules = [
                'id' => 'required|exists:post,id',
                'comment' => 'required|string|max:250'
            ]
        );

        if($validator->fails()){
            return [
                'success' => false,
                'message' => $validator->errors()->first()
            ];
        }

        $comment = new Comment();
        $comment->post_id = $postId;
        $comment->user_id = Auth::user()->id;
        $comment->text = $request->comment;
        $comment->save();

        return [
            'success' => true,
            'data' => [
                'user' => Auth::user()->name,
                'comment' => $comment->text
            ],
            'message' => 'Berhasil menambahkan komentar'
        ];
    }
}
