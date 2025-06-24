<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    private $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }
    
    public function show()
    {
        return view('users.show')->with('user', Auth::user());
    }

    public function see($user_id)
    {
        $user = $this->user->findOrFail($user_id);

        return view('users.show')->with('user', $user);
    }

    public function edit()
    {
        return view('users.edit')->with('user', Auth::user());
    }

    public function update(Request $request)
    {
        $request->validate([
            'avatar' => 'nullable|mimes:jpeg,jpg,png,gif|max:1048', // 1MB, nullable
            'name' => 'required|max:50',
            'email' => 'required|email|max:50|unique:users,email,' . Auth::id(),
            'password' => 'nullable|string|min:8|confirmed'
        ]);

        $user = $this->user->findOrFail(Auth::id());
        $user->name = $request->name;
        $user->email = $request->email;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        // --- ここからBase64変換ロジック ---
        if ($request->hasFile('avatar')) {
            $avatar_file = $request->file('avatar');
            $avatar_data = file_get_contents($avatar_file->getRealPath()); // ファイルの内容を読み込む
            $base64_avatar = 'data:image/' . $avatar_file->extension() . ';base64,' . base64_encode($avatar_data);
            $user->avatar = $base64_avatar;
        }
        // --- Base64変換ロジック ここまで ---

            $user->save();

            return redirect()->route('profile.edit')->with('success', 'Profile updated successfully!');
    }
}
