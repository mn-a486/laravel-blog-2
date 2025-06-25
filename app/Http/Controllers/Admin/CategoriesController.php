<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;

class CategoriesController extends Controller
{
    /**
     * 管理者向けカテゴリ一覧を表示
     */
    public function index()
    {
        // 全てのカテゴリを取得してビューに渡す
        $categories = Category::all();
        return view('admin.categories.index')->with('categories', $categories); 
    }

    /**
     * 新しいカテゴリを作成するフォームを表示
     */
    public function create()
    {
        return view('admin.categories.create'); 
    }


    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:20|unique:categories,name',
        ]);

        Category::create([
            'name' => $request->name,
        ]);

        return redirect()->route('admin.categories.index')->with('success', 'The category was created successfully.');
    }

    /**
     * 既存のカテゴリを編集するフォームを表示
     */
    public function edit($id)
    {
        $category = Category::findOrFail($id); // モデル取得
        return view('admin.categories.edit')->with('category', $category);
    }

    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id); // モデル取得

        $request->validate([
            'name' => 'required|string|max:20|unique:categories,name,' . $category->id,
        ]);

        $category->update([
            'name' => $request->name,
        ]);

        return redirect()->route('admin.categories.index')->with('success', 'The category was updated successfully.');
    }

    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();

        return redirect()->route('admin.categories.index')->with('success', 'The category was deleted successfully.');
    }
}
