<?php

namespace App\Http\Controllers;

use App\Models\Like;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class PostController extends Controller
{
    public function index()
    {
        return view('add-edit-post');
    }
    
    public function detail($id)
    {
        
    }
    
    public function edit($id)
    {
        $post = Post::find($id);

        return view('add-edit-post', compact('post'));
    }

    public function save(Request $request)
    {
        $request->validate([
            'id' => 'nullable|exist:post,id',
            'photo' => 'required|mimes:jpg,jpeg,png|max:2048',
            'caption' => 'nullable|string|max:250'
        ]);
        
        $post = Post::findOrNew($request->id);

        if(!empty($post->photo)){
            // if new file loaded
            if(!empty($request->photo)){
                // erase old file
                $oldPhoto = public_path('pictures/post') . '/' . $post->photo;
                
                if(File::exists($oldPhoto)){
                    File::delete($oldPhoto);
                }
            }
        }

        if(!empty($request->photo)){
            $photoLabel = str_replace(' ', '_', Auth::user()->name) . '-post-'. time() . '.' . $request->photo->extension();
            $request->photo->move(public_path('pictures/post'), $photoLabel);
        }

        $post->user_id = Auth::user()->id;
        if(!empty($request->photo)){
            $post->photo = $photoLabel;
        }
        $post->caption = $request->caption;
        $post->save();

        if($request->id){
            return redirect('/')->with('status', 'Post updated!');
        } else {
            return redirect('/')->with('status', 'New Post published!');
        }
    }

    public function likePost($id)
    {
        $post = Post::find($id);

        if($post){
            $like = Like::where('user_id', Auth::user()->id)->where('post_id', $id)->first();
            if(empty($like)){
                $like = new Like;
                $like->user_id = Auth::user()->id;
                $like->post_id = $id;
                $like->save();

                $count = Like::where('post_id', $id)->count();

                return [
                    'success' => true,
                    'like' => $count,
                    'message' => 'Berhasil suka postingan'
                ];
                // return redirect('/')->with('success', 'Berhasil suka postingan');
            } else {
                $like->delete();

                $count = Like::where('post_id', $id)->count();
                
                return [
                    'success' => true,
                    'like' => $count,
                    'message' => 'Batal suka postingan'
                ];
                // return redirect('/')->with('success', 'Batal suka postingan');
            }
        } else {
            // return redirect('/')->with('error', 'Postingan tidak ditemukan');
            return false;
        }
    }
}
