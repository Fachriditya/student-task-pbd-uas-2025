@extends('layouts.master')

@section('title', $title ?? 'Data Margin Penjualan')

@section('content')

<div class="card-table">

    <div class="table-header-container">
        
        <a href="{{ route('margin.create') }}" class="btn btn-create">
            <i class="bi bi-plus-lg"></i> Tambah Margin
        </a>

        <div class="filter-group">
            <a href="{{ route('margin.index') }}" class="btn btn-filter {{ ($title == 'Data Margin Penjualan Aktif') ? 'active' : '' }}">
                Default (Aktif)
            </a>
            <a href="{{ route('margin.all') }}" class="btn btn-filter {{ ($title == 'Data Margin Penjualan All') ? 'active' : '' }}">
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
                <th class="text-center">ID Margin</th>
                <th class="text-center">Persentase</th>
                <th>Tanggal Dibuat</th>
                <th>Tanggal Diedit</th>
                <th>Dibuat/Diedit Oleh</th>
                <th class="text-center">Status</th>
                <th class="text-center">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($margins as $margin)
                <tr>
                    <td class="text-center">{{ $margin->idmargin_penjualan ?? '-' }}</td>
                    {{-- Menggunakan kolom 'persen_display' dari View Database agar formatnya sudah '%' --}}
                    <td class="text-center fw-bold">{{ $margin->persen_display ?? ($margin->persen * 100) . '%' }}</td>
                    
                    <td>{{ $margin->created_at ? \Carbon\Carbon::parse($margin->created_at)->format('d/m/Y H:i') : '-' }}</td>
                    
                    <td>{{ $margin->updated_at ? \Carbon\Carbon::parse($margin->updated_at)->format('d/m/Y H:i') : '-' }}</td>
                    
                    <td>{{ $margin->created_by ?? '-' }}</td>
                    
                    <td class="text-center">
                        @if($margin->status == 1)
                            <span class="status-active">Aktif</span>
                        @else
                            <span class="status-inactive">Non-Aktif</span>
                        @endif
                    </td>

                    <td class="action-cell">
                        <a href="{{ route('margin.edit', $margin->idmargin_penjualan) }}" class="btn btn-edit">
                            <i class="bi bi-pencil-square"></i>
                        </a>
                        <form action="{{ route('margin.destroy', $margin->idmargin_penjualan) }}" method="POST" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-delete" onclick="return confirm('Yakin ingin hapus margin ini?')">
                                <i class="bi bi-trash-fill"></i>
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr class="empty-row">
                    <td colspan="7">
                        Tidak ada data Margin Penjualan ditemukan.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection