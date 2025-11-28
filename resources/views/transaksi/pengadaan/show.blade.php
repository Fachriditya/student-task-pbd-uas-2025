@extends('layouts.master')

@section('title', $title ?? 'Detail Pengadaan')

@push('css_extra')
    <link rel="stylesheet" href="{{ asset('css/form-master.css') }}">
@endpush

@section('content')

<div class="card-table">

    <div class="table-header-container">
        <a href="{{ route('transaksi.pengadaan.index') }}" class="btn btn-create" style="background-color: #6c757d; border-color: #6c757d;">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-header bg-light">
            <h5 class="mb-0 fw-bold">
                <i class="bi bi-info-circle me-2"></i>Informasi Pengadaan
            </h5>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-bold text-muted">Vendor</label>
                    <p class="form-control-plaintext fs-5 text-dark fw-semibold">{{ $pengadaan->nama_vendor }}</p>
                </div>
                
                <div class="col-md-6">
                    <label class="form-label fw-bold text-muted">Tanggal Pengadaan</label>
                    <p class="form-control-plaintext fs-5 text-dark">
                        {{ $pengadaan->timestamp ? \Carbon\Carbon::parse($pengadaan->timestamp)->format('d F Y, H:i') : '-' }}
                    </p>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold text-muted">Dibuat Oleh</label>
                    <p class="form-control-plaintext fs-5 text-dark">{{ $pengadaan->dibuat_oleh }}</p>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold text-muted">Status</label>
                    <div>
                        @if($pengadaan->status == 'S')
                            <span class="badge bg-success fs-6">Selesai</span>
                        @elseif($pengadaan->status == 'I')
                            <span class="badge bg-warning text-dark fs-6">In Process</span>
                        @elseif($pengadaan->status == 'C')
                            <span class="badge bg-danger fs-6">Cancelled</span>
                        @else
                            <span class="badge bg-secondary fs-6">{{ $pengadaan->status_text }}</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-header bg-light">
            <h5 class="mb-0 fw-bold">
                <i class="bi bi-shop me-2"></i>Daftar Barang 
            </h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Nama Barang</th>
                            <th class="text-center">Jumlah</th>
                            <th class="text-end">Harga Satuan</th>
                            <th class="text-end">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($details as $item)
                            <tr>
                                <td>
                                    <span class="fw-bold text-dark">{{ $item->nama_barang }}</span><br>
                                    <small class="text-muted">ID: {{ $item->idbarang }}</small>
                                </td>
                                <td class="text-center fs-5">{{ $item->jumlah }}</td>
                                <td class="text-end">{{ 'Rp ' . number_format($item->harga_satuan, 0, ',', '.') }}</td>
                                <td class="text-end fw-bold text-dark">{{ 'Rp ' . number_format($item->sub_total, 0, ',', '.') }}</td>
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
                <i class="bi bi-calculator me-2"></i>Total Pengadaan
            </h5>
        </div>
        <div class="card-body p-3">

            <div class="row mb-2">
                <div class="col-6">
                    <span class="fw-bold text-secondary">Subtotal</span>
                </div>
                <div class="col-6 text-end">
                    <span class="fw-bold text-dark">{{ 'Rp ' . number_format($pengadaan->subtotal_nilai ?? 0, 0, ',', '.') }}</span>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-6">
                    <span class="fw-bold text-secondary">PPN (10%)</span>
                </div>
                <div class="col-6 text-end">
                    <span class="fw-bold text-dark">{{ 'Rp ' . number_format($pengadaan->ppn ?? 0, 0, ',', '.') }}</span>
                </div>
            </div>

            <hr class="my-2">

            <div class="row mt-2">
                <div class="col-6">
                    <span class="h4 mb-0 fw-bolder text-dark">Total Nilai</span>
                </div>
                <div class="col-6 text-end">
                    <span class="h4 mb-0 fw-bolder text-primary">{{ 'Rp ' . number_format($pengadaan->total_nilai ?? 0, 0, ',', '.') }}</span>
                </div>
            </div>

        </div>
    </div>

</div>

@endsection