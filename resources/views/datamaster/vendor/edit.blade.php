@extends('layouts.master')

@section('title', 'Edit Vendor')

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
        <h5 class="mb-0 fw-bold">Form Edit Vendor</h5>
    </div>
    
    <div class="card-body">
        <form action="{{ route('vendor.update', $vendor->idvendor) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="form-group mb-3">
                <label for="nama_vendor" class="form-label">Nama Vendor</label>
                <input type="text" name="nama_vendor" id="nama_vendor" class="form-control" value="{{ old('nama_vendor', $vendor->nama_vendor) }}" required>
            </div>

            <div class="form-group mb-3">
                <label for="badan_hukum" class="form-label">Badan Hukum</label>
                <select name="badan_hukum" id="badan_hukum" class="form-control" required>
                    <option value="P" {{ old('badan_hukum', $vendor->badan_hukum) == 'P' ? 'selected' : '' }}>PT (Perseroan Terbatas)</option>
                    <option value="C" {{ old('badan_hukum', $vendor->badan_hukum) == 'C' ? 'selected' : '' }}>CV (Persekutuan Komanditer)</option>
                    <option value="U" {{ old('badan_hukum', $vendor->badan_hukum) == 'U' ? 'selected' : '' }}>UD (Usaha Dagang)</option>
                    <option value="F" {{ old('badan_hukum', $vendor->badan_hukum) == 'F' ? 'selected' : '' }}>Firma</option>
                </select>
            </div>

            <div class="form-group mb-3">
                <label for="status" class="form-label">Status</label>
                <select name="status" id="status" class="form-control" required>
                    <option value="A" {{ old('status', $vendor->status) == 'A' ? 'selected' : '' }}>Aktif</option>
                    <option value="N" {{ old('status', $vendor->status) == 'N' ? 'selected' : '' }}>Non-Aktif</option>
                </select>
            </div>
    
            <div class="form-actions text-end mt-4">
                <a href="{{ route('vendor.index') }}" class="btn btn-secondary me-2">Batal</a>
                <button type="submit" class="btn btn-primary">Update</button>
            </div>
        </form>
    </div>
</div>

@endsection