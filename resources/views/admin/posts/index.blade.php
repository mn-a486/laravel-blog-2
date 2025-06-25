@extends('layouts.app') {{-- layouts/app.blade.php を継承 --}}

@section('title', 'Admin Panel: Post Management') {{-- ページタイトル --}}

@section('content')
<div class="container-fluid px-4 py-5">
    <h2 class="mb-4 text-center text-secondary">Post Management</h2>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">All Posts List</h4>
        {{-- Create New Post ボタン（もしあれば） --}}
        {{-- <a href="{{ route('admin.posts.create') }}" class="btn btn-primary rounded-pill shadow-sm">
            <i class="fa-solid fa-plus-circle me-1"></i> Create New Post
        </a> --}}
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Image</th> {{-- Image column --}}
                    <th>Title</th>
                    <th>Author</th> {{-- Author column added for clarity --}}
                    <th>Created At</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($all_posts as $post)
                <tr class="{{ $post->trashed() ? 'table-secondary' : '' }}"> {{-- Soft deleted posts will have a light red background --}}
                    <td>{{ $post->id }}</td>
                    <td>
                        {{-- Post Image - 2cm square (20px by 20px) --}}
                        @if ($post->image)
                            <img src="{{ $post->image }}" alt="{{ $post->title }}" class="object-fit-cover rounded" style="width: 70px; height: 70px;">
                        @else
                            {{-- Placeholder for no image --}}
                            <img src="https://placehold.co/20x20/cccccc/ffffff?text=X" alt="No Image" class="object-fit-cover rounded" style="width: 20px; height: 20px;">
                        @endif
                    </td>
                    <td>{{ $post->title }}</td>
                    <td>
                        {{-- Display Author Name --}}
                        @if ($post->user)
                            <a href="{{ route('profile.see', $post->user->id) }}" class="text-decoration-none fw-bold">
                                @if($post->user->avatar)
                                    <img src="{{ $post->user->avatar }}" alt="{{ $post->user->name }}" class="rounded-circle me-2 object-fit-cover" style="width: 20px; height: 20px;"> {{-- Author avatar --}}
                                @else
                                    <i class="fa-solid fa-circle-user fa-xs text-secondary me-2"></i> {{-- Adjusted icon size --}}
                                @endif
                                {{ $post->user->name }}
                            </a>
                        @else
                            <span class="text-muted">Deleted User</span>
                        @endif
                    </td>
                    <td>{{ $post->created_at->format('Y/m/d H:i') }}</td>
                    <td>
                        @if($post->trashed())
                            <span class="badge bg-danger">Hidden</span>
                        @else
                            <span class="badge bg-success">Visible</span>
                        @endif
                    </td>
                    <td>
                        @if($post->trashed())
                            {{-- Restore Form (Unhide) --}}
                            <form action="{{ route('admin.posts.unhide', $post->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-outline-success btn-sm" title="Unhide Post">
                                    <i class="fa-solid fa-eye me-1"></i> Unhide
                                </button>
                            </form>
                            {{-- Permanently Delete Form (Optional) --}}
                            {{-- <form action="{{ route('admin.posts.forceDelete', $post->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to permanently delete this post? This action cannot be undone.');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger btn-sm" title="Permanently Delete Post">
                                    <i class="fa-solid fa-eraser me-1"></i> Permanently Delete
                                </button>
                            </form> --}}
                        @else
                            {{-- Hide Form (Soft Delete) --}}
                            <form action="{{ route('admin.posts.hide', $post->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger btn-sm" title="Hide Post">
                                    <i class="fa-solid fa-eye-slash me-1"></i> Hide
                                </button>
                            </form>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-4 text-muted">No posts found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination links --}}
    <div class="d-flex justify-content-center mt-4">
        {{ $all_posts->links() }}
    </div>
</div>
@endsection
