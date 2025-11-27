@extends('layouts.master')

@section('title', $title ?? 'Data Pengadaan')

@push('css_extra')
    <link rel="stylesheet" href="{{ asset('css/form-master.css') }}">
@endpush

@section('content')

<div class="card-table">

    <div class="table-header-container">
        <a href="{{ route('transaksi.pengadaan.create') }}" class="btn btn-create">
            <i class="bi bi-plus-lg"></i> Buat Pengadaan Baru
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
                <th>Dibuat Oleh</th>
                <th>Vendor</th>
                <th>Total Nilai</th>
                <th class="text-center">Status</th>
                <th class="text-center">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($pengadaans ?? [] as $data)
                <tr>
                    <td class="td-center">{{ $data->idpengadaan }}</td>
                    <td>{{ $data->timestamp ? \Carbon\Carbon::parse($data->timestamp)->format('d-m-Y H:i') : '-' }}</td>
                    
                    <td>{{ $data->dibuat_oleh }}</td>
                    <td>{{ $data->nama_vendor }}</td>
                    
                    <td>{{ 'Rp ' . number_format($data->total_nilai ?? 0, 0, ',', '.') }}</td>
                    
                    <td class="td-center">
                        @if($data->status == 'S')
                            <span class="status-active">{{ $data->status_text }}</span>
                        @elseif($data->status == 'I')
                            <span class="status-process">{{ $data->status_text }}</span>
                        @elseif($data->status == 'P')
                            <span class="status-pending">{{ $data->status_text }}</span>
                        @elseif($data->status == 'C')
                            <span class="status-cancelled">{{ $data->status_text }}</span>
                        @else
                            <span class="status-inactive">{{ $data->status_text }}</span>
                        @endif
                    </td>
                    
                    <td class="action-cell">
                        <a href="{{ route('transaksi.pengadaan.show', $data->idpengadaan) }}" class="btn btn-view">
                            <i class="bi bi-eye-fill"></i>
                        </a>
                        
                        @if($data->status == 'I')
                            <form action="{{ route('transaksi.pengadaan.cancel', $data->idpengadaan) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('PUT')
                                <button type="submit" class="btn btn-delete" onclick="return confirm('Yakin ingin membatalkan (Cancel) pengadaan ini?')" title="Batalkan Pengadaan">
                                    <i class="bi bi-x-circle-fill"></i>
                                </button>
                            </form>
                        @endif
                    </td>
                </tr>
            @empty
                <tr class="empty-row">
                    <td colspan="7"> Tidak ada data pengadaan ditemukan.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection