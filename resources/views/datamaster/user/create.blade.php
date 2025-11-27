@extends('layouts.master')

@section('title', 'Tambah User')

@push('css')
<link rel="stylesheet" href="{{ asset('css/form-master.css') }}">
@endpush

@section('content')

@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="card-table">
    <div class="form-header">
        <h3>Form Tambah User</h3>
    </div>
    
    <form action="{{ route('admin.user.store') }}" method="POST">
        @csrf
        
        <div class="form-group mb-3">
            <label for="username" class="form-label">Username</label>
            <input type="text" name="username" id="username" class="form-control" value="{{ old('username') }}" required>
        </div>

        <div class="form-group mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" name="password" id="password" class="form-control" required>
        </div>

        {{-- Input Role DIHAPUS sesuai permintaan --}}

        <div class="form-actions text-end">
            <a href="{{ route('admin.user.index') }}" class="btn btn-secondary me-2">Batal</a>
            <button type="submit" class="btn btn-primary">Simpan</button>
        </div>
    </form>
</div>
@endsection