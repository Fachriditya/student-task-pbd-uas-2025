@extends('layouts.master')

@section('title', $title ?? 'Detail Penjualan')

@push('css_extra')
    <link rel="stylesheet" href="{{ asset('css/form-master.css') }}">
@endpush

@section('content')

<div class="card-table">

    <div class="table-header-container">
        <a href="{{ route('transaksi.penjualan.index') }}" class="btn btn-create" style="background-color: #6c757d; border-color: #6c757d;">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-header bg-light">
            <h5 class="mb-0 fw-bold">
                <i class="bi bi-info-circle me-2"></i>Informasi Penjualan
            </h5>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-bold text-muted">No. Transaksi</label>
                    <p class="form-control-plaintext fs-5 text-dark fw-semibold">#{{ $penjualan->idpenjualan }}</p>
                </div>
                
                <div class="col-md-6">
                    <label class="form-label fw-bold text-muted">Tanggal</label>
                    <p class="form-control-plaintext fs-5 text-dark">
                        {{ $penjualan->created_at ? \Carbon\Carbon::parse($penjualan->created_at)->format('d F Y, H:i') : '-' }}
                    </p>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold text-muted">Kasir</label>
                    <p class="form-control-plaintext fs-5 text-dark">{{ $penjualan->dilakukan_oleh }}</p>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold text-muted">Margin Applied</label>
                    <div>
                        <span class="badge bg-info text-dark fs-6">{{ $penjualan->margin_persen * 100 }}%</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-header bg-light">
            <h5 class="mb-0 fw-bold">
                <i class="bi bi-cart-check me-2"></i>Barang Terjual
            </h5>
        </div>
        <div class="card-body p-0"> 
            <div class="table-responsive">
                <table class="dark-table-theme mb-0" style="margin-top: 0; border: none;">
                    <thead>
                        <tr>
                            <th>Nama Barang</th>
                            <th class="text-center">Qty</th>
                            <th class="text-end">Harga Jual</th>
                            <th class="text-end">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($details as $item)
                            <tr>
                                <td>
                                    <span class="fw-bold">{{ $item->nama_barang }}</span><br>
                                    <small style="color: #888;">ID: {{ $item->idbarang }}</small>
                                </td>
                                <td class="text-center fs-5">{{ $item->jumlah }}</td>
                                <td class="text-end">{{ 'Rp ' . number_format($item->harga_satuan, 0, ',', '.') }}</td>
                                <td class="text-end fw-bold">{{ 'Rp ' . number_format($item->sub_total, 0, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-4">Tidak ada detail barang.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-header bg-light">
            <h5 class="mb-0 fw-bold">
                <i class="bi bi-calculator me-2"></i>Total Pembayaran
            </h5>
        </div>
        <div class="card-body p-3">

            <div class="row mb-2">
                <div class="col-6">
                    <span class="fw-bold text-secondary">Subtotal</span>
                </div>
                <div class="col-6 text-end">
                    <span class="fw-bold text-dark">{{ 'Rp ' . number_format($penjualan->subtotal_nilai ?? 0, 0, ',', '.') }}</span>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-6">
                    <span class="fw-bold text-secondary">PPN (10%)</span>
                </div>
                <div class="col-6 text-end">
                    <span class="fw-bold text-dark">{{ 'Rp ' . number_format($penjualan->ppn ?? 0, 0, ',', '.') }}</span>
                </div>
            </div>

            <hr class="my-2">

            <div class="row mt-2">
                <div class="col-6">
                    <span class="h4 mb-0 fw-bolder text-dark">Total Nilai</span>
                </div>
                <div class="col-6 text-end">
                    <span class="h4 mb-0 fw-bolder text-primary">{{ 'Rp ' . number_format($penjualan->total_nilai ?? 0, 0, ',', '.') }}</span>
                </div>
            </div>

        </div>
    </div>

</div>

@endsection