@extends('layouts.master')

@section('title', 'Input Penerimaan')

@push('css_extra')
    <link rel="stylesheet" href="{{ asset('css/form-master.css') }}">
@endpush

@section('content')

@if ($errors->any())
    <div class="alert alert-danger">
        <ul>@foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach</ul>
    </div>
@endif

<div class="card-table">
    <div class="card-header bg-light">
        <h5 class="mb-0 fw-bold">Form Penerimaan Barang</h5>
    </div>
    
    <div class="card-body">
        <form action="{{ route('transaksi.penerimaan.create') }}" method="GET" class="mb-4">
            <div class="row g-3 align-items-end">
                <div class="col-12">
                    <label class="form-label fw-bold">Pilih Pengadaan (Status In Process)</label>
                    <select name="idpengadaan" class="form-select" required onchange="this.form.submit()">
                        <option value="">-- Pilih ID Pengadaan --</option>
                        @foreach ($pengadaans as $p)
                            <option value="{{ $p->idpengadaan }}" {{ (isset($selectedPengadaan) && $selectedPengadaan->idpengadaan == $p->idpengadaan) ? 'selected' : '' }}>
                                #{{ $p->idpengadaan }} - {{ $p->nama_vendor }} ({{ \Carbon\Carbon::parse($p->timestamp)->format('d/m/Y') }})
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </form>

        @if(isset($selectedPengadaan))
            <hr>
            <div class="alert alert-info d-flex align-items-center">
                <i class="bi bi-info-circle-fill me-2 fs-4"></i>
                <div>
                    <strong>Vendor:</strong> {{ $selectedPengadaan->nama_vendor }} <br>
                    <strong>Status Saat Ini:</strong> {{ $selectedPengadaan->status == 'P' ? 'Pending (Parsial)' : 'Baru' }}
                </div>
            </div>

            <form action="{{ route('transaksi.penerimaan.store') }}" method="POST">
                @csrf
                <input type="hidden" name="idpengadaan" value="{{ $selectedPengadaan->idpengadaan }}">

                <div class="table-responsive">
                    <table class="table table-bordered mt-3 align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th>Nama Barang</th>
                                <th class="text-center">Qty Pesan</th>
                                <th class="text-center">Sudah Terima</th>
                                <th class="text-center" style="width: 200px;">Terima Sekarang</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($detailBarang as $index => $item)
                                <tr>
                                    <td>
                                        <span class="fw-bold">{{ $item->nama_barang }}</span>
                                        <input type="hidden" name="items[{{ $index }}][idbarang]" value="{{ $item->idbarang }}">
                                    </td>
                                    <td class="text-center">{{ $item->qty_pesan }}</td>
                                    <td class="text-center">{{ $item->qty_sudah_terima }}</td>
                                    <td>
                                        <input type="number" name="items[{{ $index }}][jumlah_terima]" 
                                               class="form-control text-center" 
                                               value="{{ $item->sisa }}" 
                                               min="0" 
                                               max="{{ $item->sisa }}" 
                                               required>
                                        <div class="form-text text-center">Max: {{ $item->sisa }}</div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted p-4">
                                        Semua barang pada pengadaan ini sudah diterima lunas.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if(count($detailBarang) > 0)
                    <div class="form-actions text-end mt-4">
                        <a href="{{ route('transaksi.penerimaan.index') }}" class="btn btn-outline-secondary me-2">Batal</a>
                        <button type="submit" class="btn btn-primary btn-lg" onclick="return confirm('Pastikan jumlah fisik barang sesuai. Stok akan bertambah otomatis.')">
                            <i class="bi bi-save-fill me-1"></i> Simpan & Update Stok
                        </button>
                    </div>
                @endif
            </form>
        @endif
    </div>
</div>
@endsection