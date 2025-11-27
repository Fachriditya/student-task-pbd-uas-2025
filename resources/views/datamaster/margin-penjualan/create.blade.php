@extends('layouts.master')

@section('title', 'Tambah Margin Penjualan')

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
        <h5 class="mb-0 fw-bold">Form Tambah Margin</h5>
    </div>
    
    <div class="card-body">
        <form action="{{ route('margin.store') }}" method="POST">
            @csrf
            
            <div class="form-group mb-3">
                <label for="persen" class="form-label">Persentase Keuntungan (%)</label>
                <div class="input-group">
                    <input type="number" name="persen" id="persen" class="form-control" value="{{ old('persen') }}" step="0.01" min="0" placeholder="Contoh: 10 untuk 10%" required>
                    <span class="input-group-text">%</span>
                </div>
                <small class="text-muted">Masukkan angka saja (misal 2.5 untuk 2.5%)</small>
            </div>

            <div class="form-group mb-3">
                <label for="status" class="form-label">Status</label>
                <select name="status" id="status" class="form-control" required>
                    <option value="1" {{ old('status') == '1' ? 'selected' : '' }}>Aktif</option>
                    <option value="0" {{ old('status') == '0' ? 'selected' : '' }}>Non-Aktif</option>
                </select>
            </div>
    
            <div class="form-actions text-end mt-4">
                <a href="{{ route('margin.index') }}" class="btn btn-secondary me-2">Batal</a>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>

@endsection