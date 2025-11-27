@extends('layouts.master')

@section('title', $title ?? 'Detail Retur')

@push('css_extra')
    <link rel="stylesheet" href="{{ asset('css/form-master.css') }}">
@endpush

@section('content')

<div class="card-table">
    <div class="table-header-container">
        <a href="{{ route('transaksi.retur.index') }}" class="btn btn-create" style="background-color: #6c757d; border-color: #6c757d;">
            <i class="bi bi-arrow-left"></i> Kembali ke Daftar
        </a>
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-header bg-light"><h5 class="mb-0 fw-bold">Informasi Retur</h5></div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-bold text-muted">No. Retur</label>
                    <p class="form-control-plaintext fs-5 text-dark fw-semibold">#{{ $retur->idretur }}</p>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-bold text-muted">Ref. Penerimaan</label>
                    <p class="form-control-plaintext fs-5 text-primary fw-bold">
                        <a href="{{ route('transaksi.penerimaan.show', $retur->idpenerimaan) }}" style="text-decoration: none;">
                            #{{ $retur->idpenerimaan }} <i class="bi bi-box-arrow-up-right" style="font-size: 0.8em;"></i>
                        </a>
                    </p>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-bold text-muted">Tanggal Retur</label>
                    <p class="form-control-plaintext fs-5 text-dark">
                        {{ $retur->created_at ? \Carbon\Carbon::parse($retur->created_at)->format('d F Y, H:i') : '-' }}
                    </p>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-bold text-muted">Diproses Oleh</label>
                    <p class="form-control-plaintext fs-5 text-dark">{{ $retur->diproses_oleh }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-header bg-light"><h5 class="mb-0 fw-bold">Barang Diretur</h5></div>
        <div class="card-body p-0">
            <table class="dark-table-theme mb-0" style="margin-top: 0; border: none;">
                <thead>
                    <tr>
                        <th>Nama Barang</th>
                        <th class="text-center">Jumlah Retur</th>
                        <th>Alasan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($details as $item)
                        <tr>
                            <td>
                                <span class="fw-bold">{{ $item->nama_barang }}</span><br>
                                <small style="color: #888;">ID: {{ $item->idbarang }}</small>
                            </td>
                            <td class="text-center fs-5 text-danger fw-bold">-{{ $item->jumlah }}</td>
                            <td>{{ $item->alasan }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center text-muted py-4">Tidak ada detail barang.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection