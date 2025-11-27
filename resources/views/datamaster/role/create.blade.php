@extends('layouts.master')

@section('title', 'Tambah Role')

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
    <div class="card-header bg-light">
        <h5 class="mb-0 fw-bold">Form Tambah Role</h5>
    </div>
    
    <div class="card-body">
        <form action="{{ route('admin.role.store') }}" method="POST">
            @csrf
            
            <div class="form-group mb-3">
                <label for="nama_role" class="form-label">Nama Role</label>
                <input type="text" name="nama_role" id="nama_role" class="form-control" value="{{ old('nama_role') }}" required>
            </div>
    
            <div class="form-actions text-end mt-4">
                <a href="{{ route('admin.role.index') }}" class="btn btn-secondary me-2">Batal</a>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>

@endsection