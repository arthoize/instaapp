<?php

namespace App\Http\Controllers;

use App\Models\Follow;
use App\Models\User;
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

        $user_following = User::find($following_user_id);
        if(!$user_following){
            return redirect('user/'. $id)->with('error', 'User tidak ditemukan!');
        }

        $follow = new Follow;
        $follow->user_id = $user_id;
        $follow->following_user_id = $following_user_id;
        if($user_following->is_private == 1){
            $follow->is_accepted = 0;
            $message = 'Berhasil mengirimkan permintaan megikuti';
        } else {
            $follow->is_accepted = 1; // auto accept
            $message = 'Berhasil mengikuti';
        }
        $follow->save();

        return redirect('user/'. $id)->with('success', $message);
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
    
    public function rejectFollow($followId)
    {
        $follow = Follow::findOrFail($followId);
        $follow->delete();

        return redirect('user/'. $follow->following_user_id)->with('success', 'Berhasil menerima pengikut');
    }

    public function followRequest()
    {
        $follow = Follow::select('follow.*', 'follower.name', 'follower.username', 'follower.is_private')
                    ->join('user as follower', 'follower.id', 'follow.user_id')
                    ->where('following_user_id', Auth::user()->id)
                    ->where('is_accepted', 0)
                    ->get();

        return view('follow-request', compact('follow'));
    }
}
