@extends('layouts.app')

@section('title', 'Admin: Categories')

@section('content')
    <div class="container-fluid px-4 mt-5">
        <h2 class="text-secondary text-center mt-4">Category Management</h2>
        {{-- ここは管理者用のカテゴリ一覧ページです --}}

        {{-- Success/Error/Validation messages --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="d-flex justify-content-between align-items-center">
            <a href="{{ route('admin.categories.create') }}" class="btn btn-outline-light text-dark fs-5">
                <i class="fa-solid fa-plus"></i> Add New Category
            </a>
        </div>

        @if ($categories->isNotEmpty())
            <ul class="list-group mt-2">
                @foreach ($categories as $category)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <a href="{{ route('category.show', $category->id) }}" class="text-decoration-none h5 mb-0">
                            {{ $category->name }}
                        </a>
                        <div class="d-flex align-items-center">
                            {{-- そのカテゴリーに属する投稿数を表示 (Optional) --}}
                            <span class="badge bg-secondary rounded-pill px-3 py-2 me-5">{{ $category->posts->count() }} Posts</span>
                            @auth
                                @if (Auth::user()->isAdmin())
                                    <button class="btn btn-outline-warning btn-sm me-2 rounded-pill" data-bs-toggle="modal" data-bs-target="#editCategoryModal-{{ $category->id }}">
                                        <i class="fa-solid fa-edit"></i> Edit
                                    </button>
                                    {{-- 削除ボタン: モーダルを開く --}}
                                    <button class="btn btn-outline-danger btn-sm rounded-pill" data-bs-toggle="modal" data-bs-target="#deleteCategoryModal-{{ $category->id }}">
                                        <i class="fa-solid fa-trash-alt"></i> Delete
                                    </button>
                                @endif
                            @endauth
                        </div>
                    </li>
                @endforeach
            </ul>
        @else
            <p class="text-muted">No categories found.</p>
        @endif


@foreach ($categories as $category)
    {{-- Edit モーダル --}}
    <div class="modal fade" id="editCategoryModal-{{ $category->id }}" tabindex="-1" aria-labelledby="editCategoryModalLabel-{{ $category->id }}" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content"> {{-- Matched Posts modal style: no extra classes like rounded-3 shadow-lg --}}
                <form action="{{ route('admin.categories.update', $category->id) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <div class="modal-header"> {{-- Matched Posts modal style: no border-0 pb-0 --}}
                        <h5 class="modal-title" id="editCategoryModalLabel-{{ $category->id }}">Edit Category</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body"> {{-- Matched Posts modal style: no pt-0 --}}
                        <div class="mb-3">
                            <label for="categoryName-{{ $category->id }}" class="form-label">Category Name</label>
                            <input type="text" class="form-control" id="categoryName-{{ $category->id }}" name="name" value="{{ old('name', $category->name) }}" required>
                            {{-- Note: Validation errors for this specific form would typically be displayed after a redirect,
                                 or handled with AJAX if you implement client-side validation. --}}
                        </div>
                    </div>
                    <div class="modal-footer"> {{-- Matched Posts modal style: no border-0 pt-0 --}}
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-warning">Edit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Delete モーダル --}}
    <div class="modal fade" id="deleteCategoryModal-{{ $category->id }}" tabindex="-1" aria-labelledby="deleteCategoryModalLabel-{{ $category->id }}" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content"> {{-- Matched Posts modal style: no extra classes like rounded-3 shadow-lg --}}
                <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <div class="modal-header"> {{-- Matched Posts modal style: no border-0 pb-0 --}}
                        <h5 class="modal-title" id="deleteCategoryModalLabel-{{ $category->id }}">Delete Category</h5> {{-- Simplified title --}}
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body"> {{-- Matched Posts modal style: no pt-0 --}}
                        <p class="text-center">Do you want to delete this category?</p>
                        <p class="text-center fw-bold">{{ $category->name }}</p> {{-- Displaying category name simply --}}
                    </div>
                    <div class="modal-footer"> {{-- Matched Posts modal style: no border-0 pt-0 --}}
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endforeach
@endsection
