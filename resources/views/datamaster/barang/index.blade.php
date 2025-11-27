@extends('layouts.master')

@section('title', $title ?? 'Data Barang Aktif')

@section('content')

    <div class="card-table">

        <div class="table-header-container">
        
            <a href="{{ route('barang.create') }}" class="btn btn-create">
                <i class="bi bi-plus-lg"></i> Tambah Barang
            </a>

            <div class="filter-group">
                <a href="{{ route('barang.index') }}" class="btn btn-filter {{ ($title == 'Data Barang Aktif') ? 'active' : '' }}">
                    Default (Aktif)
                </a>
                <a href="{{ route('barang.all') }}" class="btn btn-filter {{ ($title == 'Data Barang All') ? 'active' : '' }}">
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
                    <th class="text-center">ID Barang</th>
                    <th class="text-center">Jenis</th>
                    <th>Nama Barang</th>
                    <th>Satuan</th>
                    <th>Harga Beli (Modal)</th>
                    <th class="text-center">Status</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($barangs as $barang)
                    <tr>
                        <td class="text-center">{{ $barang->idbarang ?? '-' }}</td>
                        
                        <td class="text-center">
                            {{-- Sesuai DDL View --}}
                            @if($barang->jenis == 'D') Device
                            @elseif($barang->jenis == 'C') Component
                            @elseif($barang->jenis == 'L') License
                            @elseif($barang->jenis == 'A') Accessory
                            @else Lainnya
                            @endif
                        </td>
                        
                        <td>{{ $barang->nama_barang ?? 'N/A' }}</td>
                        
                        <td>{{ $barang->nama_satuan ?? '-' }}</td>

                        <td>{{ 'Rp ' . number_format($barang->harga ?? 0, 0, ',', '.') }}</td>

                        <td class="text-center">
                            @if($barang->status == 1)
                                <span class="status-active">Aktif</span>
                            @else
                                <span class="status-inactive">Non-Aktif</span>
                            @endif
                        </td>

                        <td class="action-cell">
                            <a href="{{ route('barang.edit', $barang->idbarang) }}" class="btn btn-edit">
                                <i class="bi bi-pencil-square"></i>
                            </a>
                            <form action="{{ route('barang.destroy', $barang->idbarang) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-delete" onclick="return confirm('Yakin ingin hapus barang ini?')">
                                    <i class="bi bi-trash-fill"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr class="empty-row">
                        <td colspan="7">
                            Tidak ada data Barang ditemukan.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        
    </div>

@endsection