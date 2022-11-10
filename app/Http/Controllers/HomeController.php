<?php

namespace App\Http\Controllers;

use App\Models\Follow;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        $follow = [];
        if(Auth::check()){
            $follow = Follow::select('following_user_id')->where('user_id', Auth::user()->id)->where('is_accepted', 1)->get()->toArray();
        }

        $post = Post::with('comment.user', 'like')
                    ->select('post.*', 'user.name', 'user.username', 'user.is_private')
                    ->join('user', 'user.id', 'post.user_id')
                    ->where(function($q) use($follow){
                        if(Auth::check()){
                            $q->whereIn('post.user_id', $follow);
                            $q->orWhere('post.user_id', Auth::user()->id);
                            $q->orWhere('user.is_private', '!=', 1);
                        }else{
                            $q->where('user.is_private', '!=', 1);
                        }
                    })
                    ->orderBy('post.created_at', 'desc')
                    ->get();
                    
        if($post->isNotEmpty()){
            $post->map(function($data){
                $x = $data;
                $x->tgl_post = $data->created_at->format('Y-m-d H:i:s');
                return $x;
            });
        }
        // dd($post);
        return view('home', compact('post'));
    }

    public function user($id)
    {
        $user = User::find($id);

        if(empty($user)){
            return redirect('/')->with('error', 'User tidak ditemukan');
        }

        $is_following = 'no_request';
        $follower = Follow::where('following_user_id', $user->id)->where('is_accepted', 1)->count();
        $following = Follow::where('user_id', $user->id)->where('is_accepted', 1)->count();

        if(Auth::check()){
            $follow = Follow::where('following_user_id', $id)->where('user_id', Auth::user()->id)->first();
            if($follow){
                if($follow->is_accepted ==1){
                    $is_following = 'accepted';
                } else {
                    $is_following = 'requested';
                }
            }
        }

        $post = Post::with('comment.user', 'like')->select('post.*', 'user.name', 'user.username')
                    ->join('user', 'user.id', 'post.user_id')
                    ->where('user_id', $id)
                    ->orderBy('post.created_at', 'desc')
                    ->get();
        
        if($post->isNotEmpty()){
            $post->map(function($data){
                $x = $data;
                $x->tgl_post = $data->created_at->format('Y-m-d H:i:s');
                return $x;
            });
        }

        return view('user', compact('user', 'post', 'is_following', 'follower', 'following'));
    }

    public function searchUser(Request $request)
    {
        $user = User::select()
                    ->where(function($q)use($request){
                        $q->where('name', 'LIKE', '%'.$request->q.'%');
                        $q->orWhere('username', 'LIKE', '%'.$request->q.'%');
                    })
                    ->get();

        return view('search', compact('user'));
    }
}
