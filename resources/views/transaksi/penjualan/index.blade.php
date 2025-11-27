@extends('layouts.master')

@section('title', $title ?? 'Data Penjualan')

@push('css_extra')
    <link rel="stylesheet" href="{{ asset('css/form-master.css') }}">
@endpush

@section('content')

<div class="card-table">
    <div class="table-header-container">
        <a href="{{ route('transaksi.penjualan.create') }}" class="btn btn-create">
            <i class="bi bi-plus-lg"></i> Transaksi Baru
        </a>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if (session('error'))
         <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-bug-fill"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <table class="dark-table-theme">
        <thead>
            <tr>
                <th class="text-center">ID</th>
                <th>Tanggal</th>
                <th>Kasir</th>
                <th>Total Nilai</th>
                <th class="text-center">Margin (%)</th>
                <th class="text-center">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($penjualans as $data)
                <tr>
                    <td class="td-center">{{ $data->idpenjualan }}</td>
                    <td>{{ \Carbon\Carbon::parse($data->created_at)->format('d-m-Y H:i') }}</td>
                    <td>{{ $data->dilakukan_oleh }}</td>
                    <td>{{ 'Rp ' . number_format($data->total_nilai, 0, ',', '.') }}</td>
                    <td class="td-center">{{ $data->margin_persen ? ($data->margin_persen * 100) . '%' : '-' }}</td>
                    
                    <td class="action-cell">
                        <a href="{{ route('transaksi.penjualan.show', $data->idpenjualan) }}" class="btn btn-view" title="Lihat Detail">
                            <i class="bi bi-eye-fill"></i>
                        </a>
                    </td>
                </tr>
            @empty
                <tr class="empty-row"><td colspan="6">Belum ada data penjualan.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection