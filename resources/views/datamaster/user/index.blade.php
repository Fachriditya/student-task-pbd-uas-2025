@extends('layouts.master')

@section('title', 'Data User')

@section('content')

<div class="card-table">

    <div class="table-header-container">
        
        <a href="{{ route('admin.user.create') }}" class="btn btn-create">
            <i class="bi bi-plus-lg"></i> Tambah User
        </a>

    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <table class="dark-table-theme">
        <thead>
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Role</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($users as $user)
                <tr>
                    <td>{{ $user->iduser ?? '-' }}</td>
                    
                    <td>{{ $user->username ?? '-' }}</td>
                    {{-- Pastikan view_user memiliki kolom nama_role --}}
                    <td>{{ $user->nama_role ?? $user->idrole }}</td>

                    <td class="action-cell">
                        <a href="{{ route('admin.user.edit', $user->iduser) }}" class="btn btn-edit">
                            <i class="bi bi-pencil-square"></i>
                        </a>
                        <form action="{{ route('admin.user.destroy', $user->iduser) }}" method="POST" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-delete" onclick="return confirm('Yakin ingin hapus user ini?')">
                                <i class="bi bi-trash-fill"></i>
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr class="empty-row">
                    <td colspan="4">
                        Tidak ada data User ditemukan.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection