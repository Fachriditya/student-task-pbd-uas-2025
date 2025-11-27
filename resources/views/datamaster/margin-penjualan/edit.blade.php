@extends('layouts.master')

@section('title', 'Edit Margin Penjualan')

@push('css')
<link rel="stylesheet" href="{{ asset('css/form-master.css') }}">
@endpush

@section('content')

@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="card-table">
    <div class="card-header bg-light">
        <h5 class="mb-0 fw-bold">Form Edit Margin</h5>
    </div>
    
    <div class="card-body">
        <form action="{{ route('margin.update', $margin->idmargin_penjualan) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="form-group mb-3">
                <label for="persen" class="form-label">Persentase Keuntungan (%)</label>
                <div class="input-group">
                    {{-- Nilai di database (0.1) dikali 100 jadi (10) untuk ditampilkan --}}
                    <input type="number" name="persen" id="persen" class="form-control" value="{{ old('persen', $margin->persen * 100) }}" step="0.01" min="0" required>
                    <span class="input-group-text">%</span>
                </div>
            </div>

            <div class="form-group mb-3">
                <label for="status" class="form-label">Status</label>
                <select name="status" id="status" class="form-control" required>
                    <option value="1" {{ old('status', $margin->status) == '1' ? 'selected' : '' }}>Aktif</option>
                    <option value="0" {{ old('status', $margin->status) == '0' ? 'selected' : '' }}>Non-Aktif</option>
                </select>
            </div>
    
            <div class="form-actions text-end mt-4">
                <a href="{{ route('margin.index') }}" class="btn btn-secondary me-2">Batal</a>
                <button type="submit" class="btn btn-primary">Update</button>
            </div>
        </form>
    </div>
</div>

@endsection