@extends('layouts.app')

@section('title', 'Admin Panel: User Management')

@section('content')
<div class="container-fluid px-4 py-5">
    <h2 class="mb-4 text-center text-secondary">User Management</h2>

    <h4 class="mb-0">All Users List</h4>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>User Name</th>
                    <th>Email Address</th>
                    <th>Posts</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($all_users as $user)
                <tr class="{{ $user->trashed() ? 'table-secondary' : '' }}">
                    <td class="py-4">{{ $user->id }}</td>
                    <td>
                        <a href="{{ route('profile.see', $user->id) }}" class="text-decoration-none fw-bold d-flex align-items-center">
                            @if($user->avatar)
                                <img src="{{ $user->avatar }}" alt="{{ $user->name }}" class="rounded-circle me-2 object-fit-cover" style="width: 40px; height: 40px;">
                            @else
                                <i class="fa-solid fa-circle-user fs-1 me-2 text-secondary"></i>
                            @endif
                            {{ $user->name }}
                        </a>
                    </td>
                    <td>{{ $user->email }}</td>
                    <td class="text-center">{{ $user->posts_count }}</td>
                    <td>
                        @if($user->trashed()) {{-- ソフトデリートされているかを確認 --}}
                            <span class="badge bg-danger">Inactive</span>
                        @else
                            <span class="badge bg-success">Active</span>
                        @endif
                    </td>
                    <td>
                        @if($user->trashed())
                            {{-- アクティブ化ボタン (ソフトデリートされている場合) --}}
                            <form action="{{ route('admin.users.activate', $user->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-outline-success btn-sm" title="Activate User">
                                    <i class="fa-solid fa-circle-check me-1"></i>Activate
                                </button>
                            </form>
                        @else
                            {{-- 非アクティブ化ボタン (アクティブな場合) --}}
                            @if ($user->role === 1 || $user->id === Auth::id())
                                <button type="button" class="btn btn-outline-secondary btn-sm" title="Cannot deactivate administrator" disabled>
                                    <i class="fa-solid fa-ban me-1"></i>Deactivate
                                </button>
                            @else
                                <form action="{{ route('admin.users.deactivate', $user->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger btn-sm" title="Deactivate User">
                                        <i class="fa-solid fa-circle-xmark me-1"></i>Deactivate
                                    </button>
                                </form>
                            @endif
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-4">Not found any users.</td>
                </tr>
                @endforelse
            </tbody>
        </table>

    {{-- ページネーションリンク --}}
    <div class="d-flex justify-content-center mt-4">
        {{ $all_users->links() }}
    </div>
</div>
@endsection
