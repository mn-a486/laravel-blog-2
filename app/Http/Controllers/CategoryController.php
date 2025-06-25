<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Support\Facades\Auth; 

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::all(); // 全てのカテゴリーを取得

        return view('categories.index') // 'categories.index' ビューに渡す
                     ->with('categories', $categories);
    }

    // 特定のカテゴリーに属する投稿を表示するメソッド
    // ルートモデルバインディングを使って、IDからCategoryモデルのインスタンスを自動的に取得
    public function show(Category $category) // ここで $category を受け取る
    {
        // dd($category); // ★一時的に追加して、Categoryオブジェクトの内容を確認★

        $posts = $category->posts()->orderBy('created_at', 'desc')->paginate(10);

        return view('categories.show') // 'categories.show' ビューに渡す
                     ->with('category', $category) // 選択されたカテゴリー情報
                     ->with('posts', $posts);      // そのカテゴリーに属する投稿
    }






}
