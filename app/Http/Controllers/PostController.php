<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use App\Models\Category;

class PostController extends Controller
{
    private $post;

    public function __construct(Post $post)
    {
        $this->post = $post;
    }

    public function index()
    {
        $all_posts = $this->post->latest()->paginate(10); // 例: 1ページあたり10件表示
        $all_posts = $this->post->latest()->get();
        return view('posts.index')
            ->with('all_posts', $all_posts);
    }

    public function create()
    {
        $categories = Category::all(); // ← カテゴリ一覧を取得
        return view('posts.create', compact('categories'));
    }

    public function store(Request $request)
    {
        #1. validate the request
        $request->validate([
            'title' => 'required|max:50',
            'body' => 'required|max:1000',
            // バリデーションルールをファイルアップロード用に再設定
            'image' => 'required|mimes:jpeg,jpg,png,gif|max:1048', // 1MB
            'categories' => 'required|array|min:1',
            'category.*' => 'exists:categories,id',
        ]);

        #2. save the data to 'posts' table
        $this->post->user_id = Auth::user()->id;
        $this->post->title = $request->title;
        $this->post->body = $request->body;
        
        // --- ここからBase64変換ロジック ---
        // アップロードされたファイルをBase64にエンコードし、データURIプレフィックスを追加
        if ($request->hasFile('image')) {
            $image_file = $request->file('image');
            $image_data = file_get_contents($image_file->getRealPath()); // ファイルの内容を読み込む
            $base64_image = 'data:image/' . $image_file->extension() . ';base64,' . base64_encode($image_data);
            $this->post->image = $base64_image;
        }
        // --- Base64変換ロジック ここまで ---

        $this->post->save();

        #3. attach categories to the pivot table
        $this->post->categories()->attach($request->categories);

        #4. redirect to homepage
        return redirect()->route('index');
    }

    public function show($id)
    {
        $post = $this->post->findOrFail($id);

        return view('posts.show')
            ->with('post', $post);
    }

    public function edit($id)
    {
    $post = $this->post->findOrFail($id);

    if ($post->user->id != Auth::user()->id) {
        return redirect()->back();
    }

    $categories = Category::all(); // ← カテゴリ一覧を取得

    return view('posts.edit')
        ->with('post', $post)
        ->with('categories', $categories); // ← 渡す
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|max:50',
            'body' => 'required|max:1000',
            'image' => 'nullable|mimes:jpeg,jpg,png,gif|max:1048', // 1MB
            'categories' => 'required|array',
        ]);

        $post = $this->post->findOrFail($id);
        $post->title = $request->title;
        $post->body = $request->body;

        # if there is a new image
        if ($request->hasFile('image')) {
            $image_file = $request->file('image');
            $image_data = file_get_contents($image_file->getRealPath()); // ファイルの内容を読み込む
            $base64_image = 'data:image/' . $image_file->extension() . ';base64,' . base64_encode($image_data);
            $post->image = $base64_image;
        }

        $post->save();

        if ($request->has('categories')) {
        $post->categories()->sync($request->categories);
        }

        return redirect()->route('post.show', $id);
    }

    public function delete($id)
    {
        $post = $this->post->findOrFail($id);
        $post->delete();

        return back();
    }

    public function search(Request $request)
    {
        $keyword = $request->input('keyword');

        $posts = Post::where('title', 'like', '%' . $keyword . '%')
                ->orWhere('body', 'like', '%' . $keyword . '%')
                ->orderBy('created_at', 'desc')
                ->get(); 

        return view('posts.search')
                     ->with('posts', $posts)
                     ->with('keyword', $keyword); 
    }
}
