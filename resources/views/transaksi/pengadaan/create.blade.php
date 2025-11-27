@extends('layouts.master')

@section('title', $title ?? 'Buat Pengadaan Baru')

@section('content')

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <h5 class="alert-heading"><i class="bi bi-exclamation-triangle-fill"></i> Validasi Gagal!</h5>
            <p>Ada beberapa data yang perlu diperbaiki:</p>
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

    <form method="POST" action="{{ route('transaksi.pengadaan.store') }}">
        @csrf

        <div class="card shadow-sm mb-4">
            <div class="card-header bg-light">
                <h5 class="mb-0 fw-bold">
                    <i class="bi bi-info-circle me-2"></i>Informasi Pengadaan
                </h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="vendor_id" class="form-label">Pilih Vendor <span class="text-danger">*</span></label>
                        <select id="vendor_id" name="vendor_id" class="form-select @error('vendor_id') is-invalid @enderror" required>
                            <option value="" disabled selected>-- Pilih Vendor --</option>
                            @foreach ($vendors as $vendor)
                                <option value="{{ $vendor->idvendor }}" {{ old('vendor_id') == $vendor->idvendor ? 'selected' : '' }}>
                                    {{ $vendor->nama_vendor }}
                                </option>
                            @endforeach
                        </select>
                        @error('vendor_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="col-md-6">
                        <label for="tanggal" class="form-label">Tanggal Pengadaan</label>
                        <input type="text" id="tanggal" name="tanggal" class="form-control" value="{{ now()->format('d/m/Y H:i') }}" readonly 
                               style="background-color: #e9ecef;">
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow-sm mb-4">
            <div class="card-header bg-light">
                <h5 class="mb-0 fw-bold">
                    <i class="bi bi-shop me-2"></i>Daftar Barang 
                </h5>
            </div>
            <div class="card-body">
                
                <div class="row g-3 d-none d-md-flex item-list-header">
                    <div class="col-md-4"><label class="form-label fw-bold">Pilih Barang</label></div>
                    <div class="col-md-2"><label class="form-label fw-bold">Jumlah</label></div>
                    <div class="col-md-2"><label class="form-label fw-bold">Harga Satuan</label></div>
                    <div class="col-md-2"><label class="form-label fw-bold">Subtotal</label></div>
                    <div class="col-md-1"><label class="form-label fw-bold">Aksi</label></div>
                </div>

                <div id="item-list-container">
                    @if (old('items'))
                        @foreach (old('items') as $index => $item)
                            <div class="row g-3 item-row mb-3">
                                <div class="col-md-4">
                                    <label class="d-md-none form-label">Pilih Barang <span class="text-danger">*</span></label>
                                    <select name="items[{{ $index }}][idbarang]" class="form-select item-barang @error("items.$index.idbarang") is-invalid @enderror" required>
                                        <option value="" disabled selected>-- Pilih Barang --</option>
                                        @foreach ($barangs as $barang)
                                            <option value="{{ $barang->idbarang }}" data-harga="{{ $barang->harga }}" {{ $item['idbarang'] == $barang->idbarang ? 'selected' : '' }}>
                                                {{ $barang->nama }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2 col-6">
                                    <label class="d-md-none form-label">Jumlah <span class="text-danger">*</span></label>
                                    <input type="number" name="items[{{ $index }}][jumlah]" class="form-control item-jumlah @error("items.$index.jumlah") is-invalid @enderror" placeholder="0" required min="1" value="{{ $item['jumlah'] }}">
                                </div>
                                <div class="col-md-2 col-6">
                                    <label class="d-md-none form-label">Harga Satuan</label>
                                    <input type="number" name="items[{{ $index }}][harga_satuan]" class="form-control item-harga @error("items.$index.harga_satuan") is-invalid @enderror" placeholder="Rp 0" required value="{{ $item['harga_satuan'] }}" 
                                           readonly style="background-color: #e9ecef;">
                                </div>
                                <div class="col-md-2 col-sm-8">
                                    <label class="d-md-none form-label">Subtotal</label>
                                    <input type="text" name="items[{{ $index }}][sub_total]" class="form-control-plaintext item-subtotal" value="Rp 0" readonly>
                                </div>
                                <div class="col-md-1 col-sm-4 d-flex align-items-end">
                                    <button type="button" class="btn btn-danger item-remove-btn w-100">
                                        <i class="bi bi-trash-fill"></i>
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>

                <button type="button" id="add-item-btn" class="btn btn-primary" style="background-color: #00C4FF; border-color: #00C4FF;">
                    <i class="bi bi-plus-circle me-1"></i> Tambah Barang
                </button>
            </div>
        </div>
        
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-light">
                <h5 class="mb-0 fw-bold">
                    <i class="bi bi-calculator me-2"></i>Total Pengadaan
                </h5>
            </div>
            <div class="card-body p-3">
    
                <div class="row mb-2">
                    <div class="col-6">
                        <span class="fw-bold">Subtotal</span>
                    </div>
                    <div class="col-6 text-end">
                        <span id="summary_subtotal_text" class="fw-bold">Rp 0</span>
                        <input type="hidden" id="summary_subtotal" name="summary_subtotal" value="0">
                    </div>
                </div>
    
                <div class="row mb-3">
                    <div class="col-6">
                        <span class="fw-bold">PPN (10%)</span>
                    </div>
                    <div class="col-6 text-end">
                        <span id="summary_ppn_text" class="fw-bold">Rp 0</span>
                        <input type="hidden" id="summary_ppn" name="summary_ppn" value="0">
                    </div>
                </div>
    
                <hr class="my-2">
    
                <div class="row mt-2">
                    <div class="col-6">
                        <span class="h5 mb-0 fw-bolder">Total Nilai</span>
                    </div>
                    <div class="col-6 text-end">
                        <span id="summary_total_text" class="h5 mb-0 fw-bolder text-primary">Rp 0</span>
                        <input type="hidden" id="summary_total" name="summary_total" value="0">
                    </div>
                </div>
    
            </div>
        </div>
        
        <div class="form-actions d-flex justify-content-end mt-3">
             <a href="{{ route('transaksi.pengadaan.index') }}" class="btn btn-outline-secondary me-2">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
            <button type="submit" class="btn btn-primary btn-lg" style="min-width: 200px;">
                <i class="bi bi-save-fill me-1"></i> Simpan Pengadaan
            </button>
        </div>

    </form>

    <template id="item-row-template">
        <div class="row g-3 item-row mb-3">
            
            <div class="col-md-4">
                <label class="d-md-none form-label">Pilih Barang <span class="text-danger">*</span></label>
                <select name="items[0][idbarang]" class="form-select item-barang" required>
                    <option value="" disabled selected>-- Pilih Barang --</option>
                    @foreach ($barangs as $barang)
                        <option value="{{ $barang->idbarang }}" data-harga="{{ $barang->harga }}">{{ $barang->nama }}</option>
                    @endforeach
                </select>
            </div>
            
            <div class="col-md-2 col-6">
                <label class="d-md-none form-label">Jumlah <span class="text-danger">*</span></label>
                <input type="number" name="items[0][jumlah]" class="form-control item-jumlah" placeholder="0" required min="1" value="1">
            </div>

            <div class="col-md-2 col-6">
                <label class="d-md-none form-label">Harga Satuan</label>
                <input type="number" name="items[0][harga_satuan]" class="form-control item-harga" placeholder="Rp 0" required 
                       readonly style="background-color: #e9ecef;">
            </div>

            <div class="col-md-2 col-sm-8">
                <label class="d-md-none form-label">Subtotal</label>
                <input type="text" name="items[0][sub_total]" class="form-control-plaintext item-subtotal" value="Rp 0" readonly>
            </div>

            <div class="col-md-1 col-sm-4 d-flex align-items-end">
                <button type="button" class="btn btn-danger item-remove-btn w-100">
                    <i class="bi bi-trash-fill"></i>
                </button>
            </div>
        </div>
    </template>

@endsection

@push('css_extra')
    <link rel="stylesheet" href="{{ asset('css/form-master.css') }}">
@endpush

@push('js_extra')
    <script src="{{ asset('js/form-script.js') }}"></script>
@endpush