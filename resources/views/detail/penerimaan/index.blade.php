@extends('layouts.master')

@section('title', $title ?? 'Laporan Detail Penerimaan')

@push('css_extra')
    <link rel="stylesheet" href="{{ asset('css/form-master.css') }}">
@endpush

@section('content')

<div class="card-table">

    <div class="table-header-container">
        <h4 class="mb-0 fw-bold text-uppercase" style="color: #0A1931;">
            <i class="bi bi-box-seam me-2"></i> Laporan Detail Penerimaan Barang
        </h4>
    </div>

    <div class="table-responsive">
        <table class="dark-table-theme">
            <thead>
                <tr>
                    <th class="text-center">ID Detail</th>
                    <th class="text-center">Ref. Penerimaan</th>
                    <th>Nama Barang</th>
                    <th class="text-center">Satuan</th>
                    <th class="text-end">Harga Satuan</th>
                    <th class="text-center">Jml Terima</th>
                    <th class="text-end">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($details as $item)
                    <tr>
                        <td class="td-center">{{ $item->iddetail_penerimaan }}</td>
                        
                        <td class="td-center">
                            <a href="{{ route('transaksi.penerimaan.show', $item->idpenerimaan) }}" style="text-decoration: none; font-weight: bold; color: #3182ce;">
                                #{{ $item->idpenerimaan }}
                            </a>
                        </td>
                        
                        <td>
                            <span class="fw-bold">{{ $item->nama_barang }}</span>
                        </td>
                        
                        <td class="td-center">{{ $item->nama_satuan ?? '-' }}</td>

                        <td class="text-end">{{ 'Rp ' . number_format($item->harga_satuan_terima, 0, ',', '.') }}</td>
                        
                        <td class="td-center fs-5">{{ $item->jumlah_terima }}</td>
                        
                        <td class="text-end fw-bold">{{ 'Rp ' . number_format($item->sub_total_terima, 0, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr class="empty-row">
                        <td colspan="7">
                            Tidak ada data detail penerimaan ditemukan.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
</div>

@endsection