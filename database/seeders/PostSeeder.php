<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Post;
use App\Models\User;
use App\Models\Category;

class PostSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::first(); // 最初のユーザーに紐づけ
        
        // カテゴリーが存在することを確認し、取得する（CategorySeederが先に実行されるため取得可能）
        $category = Category::where('name', 'Travel')->first();

        $post = Post::create([ // 作成したPostインスタンスを$post変数に格納
            'user_id' => $user->id,
            'title' => 'Hello',
            'body' => 'Beautiful.', 
            'image' => 'sample.jpg', 
        ]);

        // PostとCategoryを中間テーブルで紐づけ
        if ($category) { // カテゴリが存在することを確認
            $post->categories()->attach($category->id);
        }
        
    }
}
