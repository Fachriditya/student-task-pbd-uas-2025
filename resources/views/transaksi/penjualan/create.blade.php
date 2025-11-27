@extends('layouts.master')

@section('title', 'Input Penjualan')

@push('css_extra')
    <link rel="stylesheet" href="{{ asset('css/form-master.css') }}">
@endpush

@section('content')

@if ($errors->any())
    <div class="alert alert-danger">
        <ul>@foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach</ul>
    </div>
@endif

<form method="POST" action="{{ route('transaksi.penjualan.store') }}">
    @csrf

    <div class="card-table mb-4">
        <div class="card-header bg-light d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-bold">Form Penjualan (Kasir)</h5>
            <span class="badge bg-info text-dark">Margin Aktif: {{ $persenMargin }}%</span>
        </div>
        
        <div class="card-body">
            <div class="row g-3 d-none d-md-flex item-list-header">
                <div class="col-md-4"><label class="form-label fw-bold">Pilih Barang (Stok | Harga)</label></div>
                <div class="col-md-2"><label class="form-label fw-bold">Jumlah</label></div>
                <div class="col-md-2"><label class="form-label fw-bold">Harga Satuan</label></div>
                <div class="col-md-2"><label class="form-label fw-bold">Subtotal</label></div>
                <div class="col-md-1"><label class="form-label fw-bold">Aksi</label></div>
            </div>

            <div id="item-list-container">
                <div class="row g-3 item-row mb-3">
                    <div class="col-md-4">
                        <select name="items[0][idbarang]" class="form-select item-barang" required>
                            <option value="" disabled selected>-- Pilih Barang --</option>
                            @foreach ($barangs as $barang)
                                <option value="{{ $barang->idbarang }}" 
                                        data-harga="{{ $barang->harga_jual }}" 
                                        data-stok="{{ $barang->stok_saat_ini }}"
                                        {{ $barang->stok_saat_ini <= 0 ? 'disabled' : '' }}>
                                    {{ $barang->nama }} (Stok: {{ $barang->stok_saat_ini }} | Rp {{ number_format($barang->harga_jual, 0, ',', '.') }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <input type="number" name="items[0][jumlah]" class="form-control item-jumlah" placeholder="0" required min="1" value="1">
                        <small class="text-muted stok-info" style="display: none;">Max: <span class="stok-val">0</span></small>
                    </div>
                    <div class="col-md-2">
                        <input type="text" class="form-control item-harga-display" placeholder="Rp 0" readonly style="background-color: #e9ecef;">
                        <input type="hidden" class="item-harga"> 
                    </div>
                    <div class="col-md-2">
                        <input type="text" class="form-control-plaintext item-subtotal" value="Rp 0" readonly>
                    </div>
                    <div class="col-md-1">
                        <button type="button" class="btn btn-danger item-remove-btn w-100"><i class="bi bi-trash-fill"></i></button>
                    </div>
                </div>
            </div>

            <button type="button" id="add-item-btn" class="btn btn-primary mt-3">
                <i class="bi bi-plus-circle me-1"></i> Tambah Barang
            </button>
        </div>
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-header bg-light"><h5 class="mb-0 fw-bold">Total Penjualan</h5></div>
        <div class="card-body p-3">
            <div class="row mb-2">
                <div class="col-6 fw-bold">Subtotal</div>
                <div class="col-6 text-end fw-bold" id="summary_subtotal_text">Rp 0</div>
            </div>
            <div class="row mb-3">
                <div class="col-6 fw-bold">PPN (10%)</div>
                <div class="col-6 text-end fw-bold" id="summary_ppn_text">Rp 0</div>
            </div>
            <hr>
            <div class="row mt-2">
                <div class="col-6 h4 fw-bolder">Total Bayar</div>
                <div class="col-6 text-end h4 fw-bolder text-primary" id="summary_total_text">Rp 0</div>
            </div>
        </div>
    </div>

    <div class="form-actions text-end mt-3">
        <a href="{{ route('transaksi.penjualan.index') }}" class="btn btn-outline-secondary me-2">Batal</a>
        <button type="submit" class="btn btn-primary btn-lg" onclick="return confirm('Simpan penjualan? Stok akan otomatis berkurang.')">
            <i class="bi bi-save-fill me-1"></i> Proses Penjualan
        </button>
    </div>
</form>

<template id="item-row-template">
    <div class="row g-3 item-row mb-3">
        <div class="col-md-4">
            <select name="items[0][idbarang]" class="form-select item-barang" required>
                <option value="" disabled selected>-- Pilih Barang --</option>
                @foreach ($barangs as $barang)
                    <option value="{{ $barang->idbarang }}" 
                            data-harga="{{ $barang->harga_jual }}" 
                            data-stok="{{ $barang->stok_saat_ini }}"
                            {{ $barang->stok_saat_ini <= 0 ? 'disabled' : '' }}>
                        {{ $barang->nama }} (Stok: {{ $barang->stok_saat_ini }} | Rp {{ number_format($barang->harga_jual, 0, ',', '.') }})
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <input type="number" name="items[0][jumlah]" class="form-control item-jumlah" placeholder="0" required min="1" value="1">
            <small class="text-muted stok-info" style="display: none;">Max: <span class="stok-val">0</span></small>
        </div>
        <div class="col-md-2">
            <input type="text" class="form-control item-harga-display" placeholder="Rp 0" readonly style="background-color: #e9ecef;">
            <input type="hidden" class="item-harga">
        </div>
        <div class="col-md-2">
            <input type="text" class="form-control-plaintext item-subtotal" value="Rp 0" readonly>
        </div>
        <div class="col-md-1">
            <button type="button" class="btn btn-danger item-remove-btn w-100"><i class="bi bi-trash-fill"></i></button>
        </div>
    </div>
</template>

@endsection

@push('js_extra')
    <script src="{{ asset('js/transaksi-penjualan.js') }}"></script>
@endpush