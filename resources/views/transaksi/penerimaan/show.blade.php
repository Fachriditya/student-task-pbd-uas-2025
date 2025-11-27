@extends('layouts.master')

@section('title', $title ?? 'Detail Penerimaan')

@push('css_extra')
    <link rel="stylesheet" href="{{ asset('css/form-master.css') }}">
@endpush

@section('content')

<div class="card-table">

    <div class="table-header-container">
        <a href="{{ route('transaksi.penerimaan.index') }}" class="btn btn-create" style="background-color: #6c757d; border-color: #6c757d;">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-header bg-light">
            <h5 class="mb-0 fw-bold">
                <i class="bi bi-info-circle me-2"></i>Informasi Penerimaan
            </h5>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-bold text-muted">No. Penerimaan</label>
                    <p class="form-control-plaintext fs-5 text-dark fw-semibold">#{{ $penerimaan->idpenerimaan }}</p>
                </div>
                
                <div class="col-md-6">
                    <label class="form-label fw-bold text-muted">Referensi Pengadaan</label>
                    <p class="form-control-plaintext fs-5 text-primary fw-bold">
                        <a href="{{ route('transaksi.pengadaan.show', $penerimaan->idpengadaan) }}" style="text-decoration: none;">
                            #{{ $penerimaan->idpengadaan }} <i class="bi bi-box-arrow-up-right" style="font-size: 0.8em;"></i>
                        </a>
                    </p>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold text-muted">Tanggal Terima</label>
                    <p class="form-control-plaintext fs-5 text-dark">
                        {{ $penerimaan->created_at ? \Carbon\Carbon::parse($penerimaan->created_at)->format('d F Y, H:i') : '-' }}
                    </p>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold text-muted">Diterima Oleh</label>
                    <p class="form-control-plaintext fs-5 text-dark">{{ $penerimaan->diterima_oleh }}</p>
                </div>

                <div class="col-md-12">
                    <label class="form-label fw-bold text-muted">Status</label>
                    <div>
                        <span class="badge bg-success fs-6">{{ $penerimaan->status_text }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-header bg-light">
            <h5 class="mb-0 fw-bold">
                <i class="bi bi-box-seam me-2"></i>Barang Diterima
            </h5>
        </div>
        <div class="card-body p-0"> 
            <div class="table-responsive">
                <table class="dark-table-theme mb-0" style="margin-top: 0; border: none;">
                    <thead>
                        <tr>
                            <th>Nama Barang</th>
                            <th class="text-center">Jumlah Terima</th>
                            <th class="text-end">Harga Satuan (Saat Terima)</th>
                            <th class="text-end">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($details as $item)
                            <tr>
                                <td>
                                    <span class="fw-bold">{{ $item->nama_barang }}</span><br>
                                    <small style="color: #888;">ID Barang: {{ $item->barang_idbarang }}</small>
                                </td>
                                <td class="text-center fs-5">{{ $item->jumlah_terima }}</td>
                                <td class="text-end">{{ 'Rp ' . number_format($item->harga_satuan_terima, 0, ',', '.') }}</td>
                                <td class="text-end fw-bold">{{ 'Rp ' . number_format($item->sub_total_terima, 0, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-4">Tidak ada detail barang diterima.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

@endsection