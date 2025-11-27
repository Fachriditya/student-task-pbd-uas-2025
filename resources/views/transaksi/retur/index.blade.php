@extends('layouts.master')

@section('title', $title ?? 'Data Retur')

@push('css_extra')
    <link rel="stylesheet" href="{{ asset('css/form-master.css') }}">
@endpush

@section('content')

<div class="card-table">
    <div class="table-header-container">
        <a href="{{ route('transaksi.retur.create') }}" class="btn btn-create">
            <i class="bi bi-plus-lg"></i> Buat Retur Baru
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
                <th class="text-center">ID Retur</th>
                <th>Tanggal Retur</th>
                <th>Ref. Penerimaan</th>
                <th>Diproses Oleh</th>
                <th class="text-center">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($returs as $data)
                <tr>
                    <td class="td-center">{{ $data->idretur }}</td>
                    <td>{{ \Carbon\Carbon::parse($data->created_at)->format('d-m-Y H:i') }}</td>
                    
                    <td>
                        <a href="{{ route('transaksi.penerimaan.show', $data->idpenerimaan) }}" style="text-decoration: none; font-weight: bold;">
                            #{{ $data->idpenerimaan }}
                        </a>
                    </td>
                    
                    <td>{{ $data->diproses_oleh }}</td>
                    
                    <td class="action-cell">
                        <a href="{{ route('transaksi.retur.show', $data->idretur) }}" class="btn btn-view" title="Lihat Detail">
                            <i class="bi bi-eye-fill"></i>
                        </a>
                    </td>
                </tr>
            @empty
                <tr class="empty-row"><td colspan="5">Belum ada data retur.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection