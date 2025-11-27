@extends('layouts.master')

@section('title', 'Laporan Jumlah Stok')

@push('css_extra')
    <link rel="stylesheet" href="{{ asset('css/form-master.css') }}">
    <style>
        .stok-card {
            transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
            cursor: default;
        }
        .stok-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
        }
        .stok-box {
            width: 80px;
            height: 80px;
            min-width: 80px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 12px;
        }
        .stok-number {
            font-size: 2.5rem;
            font-weight: 800;
            line-height: 1;
        }
    </style>
@endpush

@section('content')

<div class="card-table">
    
    <div class="d-flex align-items-center justify-content-between mb-4 mt-2">
        <h4 class="mb-0 fw-bold text-uppercase" style="color: #0A1931;">
            <i class="bi bi-boxes me-2"></i> Laporan Sisa Stok Barang
        </h4>
    </div>

    <div class="row row-cols-1 row-cols-md-2 row-cols-xl-3 g-4">
        @forelse ($stokBarang as $data)
            <div class="col">
                <div class="card h-100 border-0 shadow-sm stok-card" style="background-color: #fff; border-radius: 12px;">
                    <div class="card-body p-3 d-flex align-items-center">
                        
                        @php
                            if($data->stok_akhir > 10) {
                                $bgClass = 'bg-success text-white';
                            } elseif($data->stok_akhir > 0) {
                                $bgClass = 'bg-warning text-dark';
                            } else {
                                $bgClass = 'bg-danger text-white';
                            }
                        @endphp

                        <div class="stok-box {{ $bgClass }} me-3 shadow-sm">
                            <span class="stok-number">{{ $data->stok_akhir }}</span>
                        </div>
                        
                        <div class="flex-grow-1 overflow-hidden">
                            <h6 class="fw-bold text-dark mb-1 text-truncate" style="font-size: 1.1rem;" title="{{ $data->nama_barang }}">
                                {{ $data->nama_barang }}
                            </h6>
                            
                            <div class="text-muted small mb-2">
                                @if($data->jenis == 'D') Device
                                @elseif($data->jenis == 'C') Component
                                @elseif($data->jenis == 'L') License
                                @elseif($data->jenis == 'A') Accessory
                                @else Lainnya
                                @endif
                                <span class="mx-1">&bull;</span>
                                {{ $data->nama_satuan }}
                            </div>

                            <div>
                                @if($data->stok_akhir > 10)
                                    <span class="badge bg-success-subtle text-success border border-success-subtle">Banyak</span>
                                @elseif($data->stok_akhir > 0)
                                    <span class="badge bg-warning-subtle text-dark border border-warning-subtle">Sedikit</span>
                                @else
                                    <span class="badge bg-danger-subtle text-danger border border-danger-subtle">Habis</span>
                                @endif
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info text-center p-5 shadow-sm border-0" style="background-color: #fff; border-radius: 12px;" role="alert">
                    <i class="bi bi-info-circle-fill fs-1 d-block mb-3 text-info"></i>
                    <h5 class="alert-heading fw-bold">Belum Ada Data Barang</h5>
                    <p class="mb-0 text-muted">Data stok akan muncul setelah kamu menambahkan barang master.</p>
                </div>
            </div>
        @endforelse
    </div>
    
</div>

@endsection