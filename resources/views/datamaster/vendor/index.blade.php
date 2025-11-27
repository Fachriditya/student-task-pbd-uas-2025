@extends('layouts.master')

@section('title', $title ?? 'Data Vendor')

@section('content')

<div class="card-table">

    <div class="table-header-container">
        
        <a href="{{ route('vendor.create') }}" class="btn btn-create">
            <i class="bi bi-plus-lg"></i> Tambah Vendor
        </a>

        <div class="filter-group">
            <a href="{{ route('vendor.index') }}" class="btn btn-filter {{ ($title == 'Data Vendor Aktif') ? 'active' : '' }}">
                Default (Aktif)
            </a>
            <a href="{{ route('vendor.all') }}" class="btn btn-filter {{ ($title == 'Data Vendor All') ? 'active' : '' }}">
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
                <th>ID</th>
                <th>Nama Vendor</th>
                <th>Badan Hukum</th>
                <th>Status</th>
                <th class="text-center">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($vendors as $vendor)
                <tr>
                    <td>{{ $vendor->idvendor }}</td>
                    <td>{{ $vendor->nama_vendor }}</td>
                    
                    <td>
                        @if($vendor->badan_hukum == 'P') PT
                        @elseif($vendor->badan_hukum == 'C') CV
                        @elseif($vendor->badan_hukum == 'U') UD
                        @elseif($vendor->badan_hukum == 'F') Firma
                        @else Lainnya
                        @endif
                    </td>

                    <td>
                        @if($vendor->status == 'A')
                            <span class="status-active">Aktif</span>
                        @else
                            <span class="status-inactive">Non-Aktif</span>
                        @endif
                    </td>

                    <td class="action-cell">
                        <a href="{{ route('vendor.edit', $vendor->idvendor) }}" class="btn btn-edit">
                            <i class="bi bi-pencil-square"></i>
                        </a>
                        
                        <form action="{{ route('vendor.destroy', $vendor->idvendor) }}" method="POST" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-delete" onclick="return confirm('Yakin ingin hapus vendor ini?')">
                                <i class="bi bi-trash-fill"></i>
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr class="empty-row">
                    <td colspan="5">
                        Tidak ada data Vendor ditemukan.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection