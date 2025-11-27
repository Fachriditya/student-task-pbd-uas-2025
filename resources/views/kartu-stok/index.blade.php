@extends('layouts.master')

@section('title', 'Laporan Kartu Stok')

@push('css_extra')
    <link rel="stylesheet" href="{{ asset('css/form-master.css') }}">
@endpush

@section('content')

<div class="card-table">
    <div class="table-header-container">
        <h4 class="mb-0 fw-bold">Riwayat Pergerakan Barang</h4>
        {{-- Tidak ada tombol Create karena stok otomatis --}}
    </div>

    <table class="dark-table-theme">
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Nama Barang</th>
                <th>Jenis Transaksi</th>
                <th class="text-center">Masuk</th>
                <th class="text-center">Keluar</th>
                <th class="text-center">Sisa Stok</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($kartuStok as $data)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($data->created_at)->format('d/m/Y H:i') }}</td>
                    <td>
                        <span class="fw-bold">{{ $data->nama_barang }}</span>
                    </td>
                    <td>
                        @if($data->jenis_transaksi == 'P')
                            <span class="badge bg-success">Penerimaan</span>
                        @elseif($data->jenis_transaksi == 'J')
                            <span class="badge bg-primary">Penjualan</span>
                        @elseif($data->jenis_transaksi == 'R')
                            <span class="badge bg-danger">Retur</span>
                        @else
                            <span class="badge bg-secondary">Lainnya</span>
                        @endif
                        <small class="text-muted ms-1">Ref: #{{ $data->idtransaksi }}</small>
                    </td>
                    
                    <td class="text-center text-success fw-bold">
                        {{ $data->masuk > 0 ? '+' . $data->masuk : '-' }}
                    </td>
                    <td class="text-center text-danger fw-bold">
                        {{ $data->keluar > 0 ? '-' . $data->keluar : '-' }}
                    </td>
                    <td class="text-center fw-bolder bg-light">
                        {{ $data->sisa_stok }}
                    </td>
                </tr>
            @empty
                <tr class="empty-row"><td colspan="6">Belum ada riwayat stok.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection