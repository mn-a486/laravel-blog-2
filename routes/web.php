<?php

use App\Http\Controllers\PostController;
use App\Http\Controllers\CommentController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\Admin\UsersController;
use App\Http\Controllers\Admin\CategoriesController;
use App\Http\Controllers\Admin\PostsController;
use Illuminate\Support\Facades\Auth;


Auth::routes();

Route::group(['middleware' => 'auth'], function(){
    Route::get('/', [PostController::class, 'index'])->name('index');

    # POST
    Route::group(['prefix' => 'post', 'as' => 'post.'], function(){
        Route::get('/create', [PostController::class, 'create'])->name('create');
        Route::post('/store', [PostController::class, 'store'])->name('store');
        Route::get('/{id}/show', [PostController::class, 'show'])->name('show');
        Route::get('{id}/edit', [PostController::class, 'edit'])->name('edit');
        Route::patch('{id}/update', [PostController::class, 'update'])->name('update');
        Route::delete('{id}/delete', [PostController::class, 'delete'])->name('delete');
        Route::get('/search', [PostController::class, 'search'])->name('search');
    });

    # COMMENT
    Route::group(['prefix' => 'comment', 'as' => 'comment.'], function(){
        Route::post('/{post_id}/store', [CommentController::class, 'store'])->name('store');
        Route::delete('{id}/destroy', [CommentController::class, 'destroy'])->name('destroy');
        Route::get('/{id}/edit', [CommentController::class, 'edit'])->name('edit');
        Route::patch('/{id}/update', [CommentController::class, 'update'])->name('update');
    });

    # USER PROFILE
    Route::group(['prefix' => 'profile', 'as' => 'profile.'], function(){
        Route::get('/edit', [UserController::class,'edit'])->name('edit');
        Route::get('/{user_id}', [UserController::class,'see'])->name('see');
        Route::get('/', [UserController::class,'show'])->name('show');
        Route::patch('/update', [UserController::class,'update'])->name('update');
    });

    // CATEGORY
    Route::group(['prefix' => 'category', 'as' => 'category.'], function(){
        Route::get('/', [CategoryController::class, 'index'])->name('index');
        Route::get('/{category}', [CategoryController::class, 'show'])->name('show');
    });

});



// ★管理者パネル向けルート (ログイン必須 & 管理者権限チェック)
Route::group(['prefix' => 'admin', 'as' => 'admin.', 'middleware' => ['auth', 'admin']], function(){
    // adminのトップページがあればここに定義（なければ削除可能）
    // Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');

    # USERS (管理者によるユーザー管理)
    Route::group(['prefix' => 'users', 'as' => 'users.'], function(){
        Route::get('/', [UsersController::class, 'index'])->name('index');
        Route::patch('/activate/{id}', [UsersController::class, 'activate'])->name('activate');
        Route::delete('/deactivate/{id}', [UsersController::class, 'deactivate'])->name('deactivate');
        
    });

    # CATEGORIES (管理者によるカテゴリ管理)
    Route::group(['prefix' => 'categories', 'as' => 'categories.'], function(){
        Route::get('/', [CategoriesController::class, 'index'])->name('index'); // 全カテゴリ一覧
        Route::post('/store', [CategoriesController::class, 'store'])->name('store');
        Route::get('/create', [CategoriesController::class, 'create'])->name('create');
        Route::get('/{id}/edit', [CategoriesController::class, 'edit'])->name('edit');
        Route::patch('/{id}/update', [CategoriesController::class, 'update'])->name('update');
        Route::delete('/{id}/destroy', [CategoriesController::class, 'destroy'])->name('destroy');
    });

    Route::group(['prefix' => 'posts', 'as' => 'posts.'], function(){
        Route::get('/', [PostsController::class, 'index'])->name('index');
        Route::patch('/unhide/{id}', [PostsController::class, 'unhide'])->name('unhide');
        Route::delete('/hide/{id}', [PostsController::class, 'hide'])->name('hide');
    });
});
