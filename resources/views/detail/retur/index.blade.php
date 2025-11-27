@extends('layouts.master')

@section('title', $title ?? 'Laporan Detail Retur')

@push('css_extra')
    <link rel="stylesheet" href="{{ asset('css/form-master.css') }}">
@endpush

@section('content')

<div class="card-table">

    <div class="table-header-container">
        <h4 class="mb-0 fw-bold text-uppercase" style="color: #0A1931;">
            <i class="bi bi-arrow-return-left me-2"></i> Laporan Detail Retur Barang
        </h4>
    </div>

    <div class="table-responsive">
        <table class="dark-table-theme">
            <thead>
                <tr>
                    <th class="text-center">ID Detail</th>
                    <th class="text-center">Ref. Retur</th>
                    <th>Nama Barang</th>
                    <th class="text-center">Jumlah Retur</th>
                    <th>Alasan Retur</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($details as $item)
                    <tr>
                        <td class="td-center">{{ $item->iddetail_retur }}</td>
                        
                        <td class="td-center">
                            <a href="{{ route('transaksi.retur.show', $item->idretur) }}" style="text-decoration: none; font-weight: bold; color: #3182ce;">
                                #{{ $item->idretur }}
                            </a>
                        </td>
                        
                        <td>
                            <span class="fw-bold">{{ $item->nama_barang }}</span>
                        </td>

                        <td class="td-center fs-5 text-danger fw-bold">
                            -{{ $item->jumlah_retur }}
                        </td>
                        
                        <td>
                            <span class="fst-italic text-muted">{{ $item->alasan }}</span>
                        </td>
                    </tr>
                @empty
                    <tr class="empty-row">
                        <td colspan="5">
                            Tidak ada data detail retur ditemukan.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
</div>

@endsection