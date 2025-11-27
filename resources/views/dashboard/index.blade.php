@extends('layouts.master')

@section('title', 'Dashboard Utama')

@section('content')
    <h3>Selamat Datang, {{ $nama_user }}!</h3>
    <p>
        Ini adalah ringkasan data dari seluruh sistem inventori.
    </p>
    
    <div class="card-table">
        
    <h4 class="summary-header">Ringkasan Data Master (Aktif)</h4>
    <div class="summary-grid-master">
        <div class="summary-card">
            <i class="bi bi-people-fill"></i>
            <div class="card-content">
                <h2>{{ $total_user }}</h2>
                <span>Total User</span>
            </div>
        </div>
        <div class="summary-card">
            <i class="bi bi-person-rolodex"></i>
            <div class="card-content">
                <h2>{{ $total_role }}</h2>
                <span>Total Role</span>
            </div>
        </div>
        <div class="summary-card">
            <i class="bi bi-truck"></i>
            <div class="card-content">
                <h2>{{ $total_vendor }}</h2>
                <span>Vendor Aktif</span>
            </div>
        </div>
        <div class="summary-card">
            <i class="bi bi-box-seam-fill"></i>
            <div class="card-content">
                <h2>{{ $total_barang }}</h2>
                <span>Barang Aktif</span>
            </div>
        </div>
        <div class="summary-card">
            <i class="bi bi-rulers"></i>
            <div class="card-content">
                <h2>{{ $total_satuan }}</h2>
                <span>Satuan Aktif</span>
            </div>
        </div>
        <div class="summary-card">
            <i class="bi bi-graph-up-arrow"></i>
            <div class="card-content">
                <h2>{{ $total_margin }}</h2>
                <span>Margin Aktif</span>
            </div>
        </div>
    </div>
    <h4 class="summary-header">Ringkasan Data Transaksi</h4>
    <div class="summary-grid">
        <div class="summary-card">
            <i class="bi bi-cart-plus-fill"></i>
            <div class="card-content">
                <h2>{{ $total_pengadaan }}</h2>
                <span>Total Pengadaan</span>
            </div>
        </div>
        <div class="summary-card">
            <i class="bi bi-box-arrow-in-down"></i>
            <div class="card-content">
                <h2>{{ $total_penerimaan }}</h2>
                <span>Total Penerimaan</span>
            </div>
        </div>
        <div class="summary-card">
            <i class="bi bi-cash-coin"></i>
            <div class="card-content">
                <h2>{{ $total_penjualan }}</h2>
                <span>Total Penjualan</span>
            </div>
        </div>
        <div class="summary-card">
            <i class="bi bi-arrow-return-left"></i>
            <div class="card-content">
                <h2>{{ $total_retur }}</h2>
                <span>Total Retur</span>
            </div>
        </div>
    </div>
@endsection