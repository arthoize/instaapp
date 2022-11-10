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

        $follow = new Follow;
        $follow->user_id = $user_id;
        $follow->following_user_id = $following_user_id;
        $follow->save();

        return redirect('user/'. $id)->with('success', 'Berhasil megikuti');
    } 
}
