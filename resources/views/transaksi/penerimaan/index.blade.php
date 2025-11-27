@extends('layouts.master')

@section('title', $title ?? 'Data Penerimaan')

@push('css_extra')
    <link rel="stylesheet" href="{{ asset('css/form-master.css') }}">
@endpush

@section('content')

<div class="card-table">
    <div class="table-header-container">
        <a href="{{ route('transaksi.penerimaan.create') }}" class="btn btn-create">
            <i class="bi bi-plus-lg"></i> Terima Barang
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
                <th>Tanggal Terima</th>
                <th class="text-center">ID Pengadaan</th>
                <th>Diterima Oleh</th>
                <th class="text-center">Status</th>
                <th class="text-center">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($penerimaans as $data)
                <tr>
                    <td class="td-center">{{ $data->idpenerimaan }}</td>
                    <td>{{ \Carbon\Carbon::parse($data->created_at)->format('d-m-Y H:i') }}</td>
                    
                    <td class="td-center">
                        <a href="{{ route('transaksi.pengadaan.show', $data->idpengadaan) }}" style="text-decoration: none; font-weight: bold; color: #3182ce;">
                            #{{ $data->idpengadaan }}
                        </a>
                    </td>
                    
                    <td>{{ $data->diterima_oleh }}</td>
                    
                    <td class="td-center">
                        @if($data->status == 'S')
                            <span class="status-active">{{ $data->status_text }}</span>
                        @elseif($data->status == 'P')
                            <span class="status-pending">{{ $data->status_text }}</span>
                        @elseif($data->status == 'C')
                            <span class="status-cancelled">{{ $data->status_text }}</span>
                        @else
                            <span class="status-inactive">{{ $data->status_text }}</span>
                        @endif
                    </td>
                    
                    <td class="action-cell">
                        <a href="{{ route('transaksi.penerimaan.show', $data->idpenerimaan) }}" class="btn btn-view" title="Lihat Detail">
                            <i class="bi bi-eye-fill"></i>
                        </a>
                    </td>
                </tr>
            @empty
                <tr class="empty-row"><td colspan="6">Belum ada data penerimaan.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection