@extends('layouts.master')

@section('title', 'Tambah Satuan')

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
        <h5 class="mb-0 fw-bold">Form Tambah Satuan</h5>
    </div>
    
    <div class="card-body">
        <form action="{{ route('satuan.store') }}" method="POST">
            @csrf
            
            <div class="form-group mb-3">
                <label for="nama_satuan" class="form-label">Nama Satuan</label>
                <input type="text" name="nama_satuan" id="nama_satuan" class="form-control" value="{{ old('nama_satuan') }}" required>
            </div>

            <div class="form-group mb-3">
                <label for="status" class="form-label">Status</label>
                <select name="status" id="status" class="form-control" required>
                    <option value="1" {{ old('status') == '1' ? 'selected' : '' }}>Aktif</option>
                    <option value="0" {{ old('status') == '0' ? 'selected' : '' }}>Non-Aktif</option>
                </select>
            </div>
    
            <div class="form-actions text-end mt-4">
                <a href="{{ route('satuan.index') }}" class="btn btn-secondary me-2">Batal</a>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>

@endsection