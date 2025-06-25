<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;

class PostsController extends Controller
{
    /**
     * @var Post $post
     * Post モデルのインスタンス
     */
    private $post;

    /**
     * コンストラクタでPostモデルを注入
     *
     * @param Post $post
     */
    public function __construct(Post $post)
    {
        $this->post = $post;
    }

    /**
     * 管理者パネル用の全投稿一覧を表示。
     * ソフトデリートされた投稿も含まれる。
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // ソフトデリートされた投稿も含む全ての投稿を最新のものから5件ずつページネートして取得
        $all_posts = $this->post->withTrashed()->latest()->paginate(5);

        // admin.posts.indexビューに取得した投稿データを渡して表示
        return view('admin.posts.index')->with('all_posts', $all_posts);
    }

    /**
     * 投稿をソフトデリート（非表示）する。
     *
     * @param int $id ソフトデリートする投稿のID
     * @return \Illuminate\Http\RedirectResponse
     */
    public function hide($id)
    {
        // 指定されたIDの投稿をソフトデリート
        $this->post->destroy($id);

        // 前のページにリダイレクト
        return redirect()->back()->with('success', 'Post hidden successfully.');
    }

    /**
     * ソフトデリートされた投稿を復元（再表示）する。
     *
     * @param int $id 復元する投稿のID
     * @return \Illuminate\Http\RedirectResponse
     */
    public function unhide($id)
    {
        // ソフトデリートされた投稿の中から指定されたIDのものを探し、復元
        $this->post->onlyTrashed()->findOrFail($id)->restore();

        // 前のページにリダイレクト
        return redirect()->back()->with('success', 'Post unhidden successfully.');
    }
}
