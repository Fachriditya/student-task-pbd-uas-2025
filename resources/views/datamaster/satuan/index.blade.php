@extends('layouts.master')

@section('title', $title ?? 'Data Satuan Aktif')

@section('content')

<div class="card-table">

    <div class="table-header-container">
        
        <a href="{{ route('satuan.create') }}" class="btn btn-create">
            <i class="bi bi-plus-lg"></i> Tambah Satuan
        </a>

        <div class="filter-group">
            <a href="{{ route('satuan.index') }}" class="btn btn-filter {{ ($title == 'Data Satuan Aktif') ? 'active' : '' }}">
                Default (Aktif)
            </a>
            <a href="{{ route('satuan.all') }}" class="btn btn-filter {{ ($title == 'Data Satuan All') ? 'active' : '' }}">
                Show All
            </a>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <table class="dark-table-theme">
        <thead>
            <tr>
                <th class="text-center">ID Satuan</th>
                <th>Nama Satuan</th>
                <th class="text-center">Status</th>
                <th class="text-center">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($satuans as $satuan)
                <tr>
                    <td class="text-center">{{ $satuan->idsatuan ?? 'N/A' }}</td>
                    <td>{{ $satuan->nama_satuan ?? 'N/A' }}</td>
                    
                    <td class="text-center">
                        @if($satuan->status == 1)
                            <span class="status-active">Aktif</span>
                        @else
                            <span class="status-inactive">Non-Aktif</span>
                        @endif
                    </td>

                    <td class="action-cell">
                        <a href="{{ route('satuan.edit', $satuan->idsatuan) }}" class="btn btn-edit">
                            <i class="bi bi-pencil-square"></i>
                        </a>
                        <form action="{{ route('satuan.destroy', $satuan->idsatuan) }}" method="POST" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-delete" onclick="return confirm('Yakin ingin hapus satuan ini?')">
                                <i class="bi bi-trash-fill"></i>
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr class="empty-row">
                    <td colspan="4">
                        Tidak ada data Satuan ditemukan.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection