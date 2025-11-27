@extends('layouts.master')

@section('title', 'Edit Barang')

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
        <h5 class="mb-0 fw-bold">Form Edit Barang</h5>
    </div>
    
    <div class="card-body">
        <form action="{{ route('barang.update', $barang->idbarang) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="form-group mb-3">
                <label for="nama" class="form-label">Nama Barang</label>
                <input type="text" name="nama" id="nama" class="form-control" value="{{ old('nama', $barang->nama) }}" required>
            </div>

            <div class="form-group mb-3">
                <label for="jenis" class="form-label">Jenis Barang</label>
                <select name="jenis" id="jenis" class="form-control" required>
                    <option value="D" {{ old('jenis', $barang->jenis) == 'D' ? 'selected' : '' }}>Device</option>
                    <option value="C" {{ old('jenis', $barang->jenis) == 'C' ? 'selected' : '' }}>Component</option>
                    <option value="L" {{ old('jenis', $barang->jenis) == 'L' ? 'selected' : '' }}>License</option>
                    <option value="A" {{ old('jenis', $barang->jenis) == 'A' ? 'selected' : '' }}>Accessory</option>
                </select>
            </div>

            <div class="form-group mb-3">
                <label for="idsatuan" class="form-label">Satuan</label>
                <select name="idsatuan" id="idsatuan" class="form-control" required>
                    <option value="" disabled>-- Pilih Satuan --</option>
                    @foreach ($satuans as $satuan)
                        <option value="{{ $satuan->idsatuan }}" {{ old('idsatuan', $barang->idsatuan) == $satuan->idsatuan ? 'selected' : '' }}>
                            {{ $satuan->nama_satuan }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group mb-3">
                <label for="harga" class="form-label">Harga Beli (Modal)</label>
                <input type="number" name="harga" id="harga" class="form-control" value="{{ old('harga', $barang->harga) }}" min="0" required>
            </div>

            <div class="form-group mb-3">
                <label for="status" class="form-label">Status</label>
                <select name="status" id="status" class="form-control" required>
                    <option value="1" {{ old('status', $barang->status) == '1' ? 'selected' : '' }}>Aktif</option>
                    <option value="0" {{ old('status', $barang->status) == '0' ? 'selected' : '' }}>Non-Aktif</option>
                </select>
            </div>
    
            <div class="form-actions text-end mt-4">
                <a href="{{ route('barang.index') }}" class="btn btn-secondary me-2">Batal</a>
                <button type="submit" class="btn btn-primary">Update</button>
            </div>
        </form>
    </div>
</div>

@endsection