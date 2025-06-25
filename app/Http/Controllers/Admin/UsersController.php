<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UsersController extends Controller
{

    /**
     * Display a listing of the users for admin.
     * 管理者はソフトデリートされたユーザーも含めて全てのユーザーを一覧表示します。
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $all_users = User::withTrashed()->withCount('posts')->latest()->paginate(10);
        return view('admin.users.index')->with('all_users', $all_users);
    }

    /**
     * Soft delete (deactivate) a user.
     *
     * @param int $id 非アクティブ化（ソフトデリート）するユーザーのID
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deactivate(int $id)
    {
        // 指定されたIDのユーザーを検索し、存在しない場合は404エラー
        $user = User::findOrFail($id);

        if ($user->role === 1 || $user->id === Auth::id()) {
            return redirect()->back()->with('error', 'Administrator accounts cannot be deactivated.');
        }

        $user->delete();

        return redirect()->back();
    }

    /**
     * Activate (restore) a user from soft delete.
     *
     * @param int $id アクティブ化（復元）するユーザーのID
     * @return \Illuminate\Http\RedirectResponse
     */
    public function activate(int $id)
    {
        $user = User::withTrashed()->findOrFail($id);
        $user->restore();

        return redirect()->back();
    }
}
