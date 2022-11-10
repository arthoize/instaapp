<?php

namespace App\Http\Controllers;

use App\Models\Follow;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FollowController extends Controller
{
    public function follow($id)
    {
        $user_id = Auth::user()->id;
        $following_user_id = $id;

        $exist = Follow::where('user_id', $user_id)->where('following_user_id', $following_user_id)->first();
        if($exist){
            return redirect('user/'. $id)->with('error', 'Anda sudah mengikuti akun ini');
        }

        $follow = new Follow;
        $follow->user_id = $user_id;
        $follow->following_user_id = $following_user_id;
        $follow->is_accepted = 0;
        $follow->save();

        return redirect('user/'. $id)->with('success', 'Berhasil mengirimkan permintaan megikuti');
    }
    
    public function unfollow($id)
    {
        $user_id = Auth::user()->id;
        $following_user_id = $id;

        $exist = Follow::where('user_id', $user_id)->where('following_user_id', $following_user_id)->first();
        if($exist){
            $exist->delete();
            return redirect('user/'. $id)->with('success', 'Berhasil berhenti mengikuti');
        }

        return redirect('user/'. $id)->with('error', 'Anda belum mengikuti akun ini');
    }

    public function acceptFollow($followId)
    {
        $follow = Follow::findOrFail($followId);
        $follow->is_accepted = 1;
        $follow->save();

        return redirect('user/'. $follow->following_user_id)->with('success', 'Berhasil menerima pengikut');
    }
}
