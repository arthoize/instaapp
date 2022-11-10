<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    public function profile()
    {
        $user = Auth::user();

        return view('profile', compact('user'));
    }

    public function saveProfile(Request $request)
    {
        $request->validate([
            'name' => 'required|max:250',
            'username' => [
                'required',
                Rule::unique('user', 'username')->ignore(Auth::user()->id)
            ],
            'email' => 'required|email',
            'profile_description' => 'nullable|string|max:250',
            'is_private' => 'required|in:0,1'
        ]);

        $user = User::find(Auth::user()->id);
        $user->name     = $request->name;
        $user->username = $request->username;
        $user->email    = $request->email;
        $user->profile_description    = $request->profile_description;
        $user->is_private    = $request->is_private;
        $user->save();

        return redirect('user/' . $user->id)->with('success', 'Profile updated!');
    }
}
