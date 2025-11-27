@extends('layouts.master')

@section('title', 'Input Retur')

@push('css_extra')
    <link rel="stylesheet" href="{{ asset('css/form-master.css') }}">
@endpush

@section('content')

@if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <h5 class="alert-heading"><i class="bi bi-exclamation-triangle-fill"></i> Validasi Gagal!</h5>
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if (session('error'))
     <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <h5 class="alert-heading"><i class="bi bi-bug-fill"></i> Terjadi Kesalahan!</h5>
        <p>{{ session('error') }}</p>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if (session('success'))
     <div class="alert alert-success alert-dismissible fade show" role="alert">
        <h5 class="alert-heading"><i class="bi bi-check-circle-fill"></i> Berhasil!</h5>
        <p>{{ session('success') }}</p>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="card-table">
    <div class="card-header bg-light">
        <h5 class="mb-0 fw-bold">Form Retur Pembelian</h5>
    </div>
    
    <div class="card-body">
        <form action="{{ route('transaksi.retur.create') }}" method="GET" class="mb-4">
            <div class="row g-3 align-items-end">
                <div class="col-12">
                    <label class="form-label fw-bold">Pilih Penerimaan (Sumber Barang)</label>
                    <select name="idpenerimaan" class="form-select" required onchange="this.form.submit()">
                        <option value="">-- Pilih ID Penerimaan --</option>
                        @foreach ($penerimaans as $p)
                            <option value="{{ $p->idpenerimaan }}" {{ (isset($selectedPenerimaan) && $selectedPenerimaan->idpenerimaan == $p->idpenerimaan) ? 'selected' : '' }}>
                                #{{ $p->idpenerimaan }} (PO #{{ $p->idpengadaan }}) - {{ $p->nama_vendor }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </form>

        @if(isset($selectedPenerimaan))
            <hr>
            <div class="alert alert-warning d-flex align-items-center">
                <i class="bi bi-exclamation-triangle-fill me-2 fs-4"></i>
                <div>
                    <strong>Peringatan:</strong> Retur akan mengurangi stok gudang secara otomatis. Pastikan barang fisik benar-benar dikembalikan ke Vendor: <strong>{{ $selectedPenerimaan->nama_vendor }}</strong>.
                </div>
            </div>

            <form action="{{ route('transaksi.retur.store') }}" method="POST">
                @csrf
                <input type="hidden" name="idpenerimaan" value="{{ $selectedPenerimaan->idpenerimaan }}">

                <div class="table-responsive">
                    <table class="table table-bordered mt-3 align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th>Nama Barang</th>
                                <th class="text-center">Qty Diterima</th>
                                <th class="text-center">Sudah Diretur</th>
                                <th class="text-center" style="width: 150px;">Jml Retur</th>
                                <th>Alasan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($detailBarang as $index => $item)
                                @php $sisa = $item->jumlah_terima - $item->qty_sudah_retur; @endphp
                                @if($sisa > 0)
                                <tr>
                                    <td>
                                        <span class="fw-bold">{{ $item->nama_barang }}</span>
                                        <input type="hidden" name="items[{{ $index }}][iddetail_penerimaan]" value="{{ $item->iddetail_penerimaan }}">
                                    </td>
                                    <td class="text-center">{{ $item->jumlah_terima }}</td>
                                    <td class="text-center">{{ $item->qty_sudah_retur }}</td>
                                    <td>
                                        <input type="number" name="items[{{ $index }}][jumlah_retur]" 
                                               class="form-control text-center" 
                                               min="0" 
                                               max="{{ $sisa }}" 
                                               placeholder="0">
                                        <div class="form-text text-center">Max: {{ $sisa }}</div>
                                    </td>
                                    <td>
                                        <input type="text" name="items[{{ $index }}][alasan]" class="form-control" placeholder="Contoh: Rusak/Cacat">
                                    </td>
                                </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="form-actions text-end mt-4">
                    <a href="{{ route('transaksi.retur.index') }}" class="btn btn-outline-secondary me-2">Batal</a>
                    <button type="submit" class="btn btn-danger btn-lg" onclick="return confirm('Yakin simpan retur? Stok akan berkurang.')">
                        <i class="bi bi-arrow-return-left me-1"></i> Proses Retur
                    </button>
                </div>
            </form>
        @endif
    </div>
</div>
@endsection