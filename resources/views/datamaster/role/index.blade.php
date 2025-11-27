@extends('layouts.master')

@section('title', $title ?? 'Data Role')

@section('content')

<div class="card-table">

    <div class="table-header-container">
        <a href="{{ route('admin.role.create') }}" class="btn btn-create">
            <i class="bi bi-plus-lg"></i> Tambah Role
        </a>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <table class="dark-table-theme">
        <thead>
            <tr>
                <th>ID Role</th>
                <th>Nama Role</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($roles as $role)
                <tr>
                    <td>{{ $role->idrole }}</td>
                    <td>{{ $role->nama_role }}</td>
                    <td class="action-cell">
                        <a href="{{ route('admin.role.edit', $role->idrole) }}" class="btn btn-edit">
                            <i class="bi bi-pencil-square"></i>
                        </a>
                        <form action="{{ route('admin.role.destroy', $role->idrole) }}" method="POST" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-delete" onclick="return confirm('Yakin ingin hapus role ini?')">
                                <i class="bi bi-trash-fill"></i>
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr class="empty-row">
                    <td colspan="3">Tidak ada data Role ditemukan.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection