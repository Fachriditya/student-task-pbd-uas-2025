<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') | Sistem Inventori</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/data-master.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    @stack('css_extra')
</head>
<body>

    <div class="sidebar">
        <h3 class="logo-text">Menu</h3>

        @if(Auth::user()->idrole == 1)
            <a href="{{ route('dashboard.index') }}" class="sidebar-link">
                <i class="bi bi-speedometer2"></i> 
                <span>Dashboard</span>
            </a>
            
            <div class="dropdown-container">
                <button class="dropdown-btn" type="button">
                    <i class="bi bi-box2-heart"></i> 
                    <span>DATA STOK</span>
                    <span class="dropdown-caret">▼</span>
                </button>
                <div class="dropdown-content">
                    <a href="{{ route('kartu-stok.index') }}" class="sidebar-sub-menu">Kartu Stok</a>
                    <a href="{{ route('stok-barang.index') }}" class="sidebar-sub-menu">Stok Barang</a>
                </div>
            </div>

            <div class="dropdown-container">
                <button class="dropdown-btn" type="button">
                    <i class="bi bi-people-fill"></i> 
                    <span>DATA USER & ROLE</span>
                    <span class="dropdown-caret">▼</span>
                </button>
                <div class="dropdown-content">
                    <a href="{{ route('admin.user.index') }}" class="sidebar-sub-menu">User</a>
                    <a href="{{ route('admin.role.index') }}" class="sidebar-sub-menu">Role</a>
                </div>
            </div>

            <div class="dropdown-container">
                <button class="dropdown-btn" type="button">
                    <i class="bi bi-box-seam"></i> 
                    <span>DATA MASTER</span>
                    <span class="dropdown-caret">▼</span>
                </button>
                <div class="dropdown-content">
                    <a href="{{ route('vendor.index') }}" class="sidebar-sub-menu">Vendor</a>
                    <a href="{{ route('barang.index') }}" class="sidebar-sub-menu">Barang</a>
                    <a href="{{ route('satuan.index') }}" class="sidebar-sub-menu">Satuan</a>
                    <a href="{{ route('margin.index') }}" class="sidebar-sub-menu">Margin Penjualan</a>
                </div>
            </div>

            <div class="dropdown-container">
                <button class="dropdown-btn" type="button">
                    <i class="bi bi-cart-fill"></i> 
                    <span>DATA TRANSAKSI</span>
                    <span class="dropdown-caret">▼</span>
                </button>
                <div class="dropdown-content">
                    <a href="{{ route('transaksi.pengadaan.index') }}" class="sidebar-sub-menu">Pengadaan</a>
                    <a href="{{ route('transaksi.penerimaan.index') }}" class="sidebar-sub-menu">Penerimaan</a>
                    <a href="{{ route('transaksi.penjualan.index') }}" class="sidebar-sub-menu">Penjualan</a>
                    <a href="{{ route('transaksi.retur.index') }}" class="sidebar-sub-menu">Retur</a>
                </div>
            </div> 

            <div class="dropdown-container">
                <button class="dropdown-btn" type="button">
                    <i class="bi bi-receipt-cutoff"></i> 
                    <span>DETAIL TRANSAKSI</span>
                    <span class="dropdown-caret">▼</span>
                </button>
                <div class="dropdown-content">
                    <a href="{{ route('detail.pengadaan.index') }}" class="sidebar-sub-menu">Detail Pengadaan</a>
                    <a href="{{ route('detail.penerimaan.index') }}" class="sidebar-sub-menu">Detail Penerimaan</a>
                    <a href="{{ route('detail.penjualan.index') }}" class="sidebar-sub-menu">Detail Penjualan</a>
                    <a href="{{ route('detail.retur.index') }}" class="sidebar-sub-menu">Detail Retur</a>
                </div>
            </div>

        @elseif(Auth::user()->idrole == 2)
            <a href="{{ route('stok-barang.index') }}" class="sidebar-link">
                <i class="bi bi-speedometer2"></i>
                <span>Dashboard (Stok)</span>
            </a>

            <a href="{{ route('transaksi.penjualan.index') }}" class="sidebar-link">
                <i class="bi bi-cart-check-fill"></i> 
                <span>Penjualan</span>
            </a>

            <a href="{{ route('detail.penjualan.index') }}" class="sidebar-link">
                <i class="bi bi-receipt-cutoff"></i> 
                <span>Detail Penjualan</span>
            </a>
            
        @endif

        <hr class="sidebar-divider" style="margin-top: 20px;">

        <form action="{{ route('logout') }}" method="POST" style="margin: 0; padding: 0;">
            @csrf
            <button type="submit" class="sidebar-link-logout">
                <i class="bi bi-box-arrow-left"></i> 
                <span>LOGOUT</span>
            </button>
        </form>
    </div>

    <div class="main-wrapper">
        <main class="content-area">
            <div class="container-fluid">
                <div class="page-title-card">
                    <h2 class="page-title">@yield('title', 'Dashboard')</h2>
                </div>
                
                <div>
                    @yield('content') 
                </div>
            </div>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/sidebar.js') }}"></script>
    
    @stack('js_extra')
</body>
</html>