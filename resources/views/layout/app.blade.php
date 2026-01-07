<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Sistem Penggajian')</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
    
    <style>
        :root {
            --sidebar-width: 260px;
            --primary-color: #4f46e5;
            --sidebar-bg: #1e293b;
            --sidebar-hover: #334155;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
        }
        
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: var(--sidebar-width);
            background: var(--sidebar-bg);
            color: white;
            transition: all 0.3s;
            z-index: 1000;
            overflow-y: auto;
        }
        
        .sidebar .logo {
            padding: 20px;
            text-align: center;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        
        .sidebar .logo h4 {
            margin: 0;
            font-weight: 600;
        }
        
        .sidebar-menu {
            list-style: none;
            padding: 0;
            margin: 20px 0;
        }
        
        .sidebar-menu li a {
            display: flex;
            align-items: center;
            padding: 12px 20px;
            color: #cbd5e1;
            text-decoration: none;
            transition: all 0.3s;
        }
        
        .sidebar-menu li a:hover,
        .sidebar-menu li a.active {
            background: var(--sidebar-hover);
            color: white;
            border-left: 4px solid var(--primary-color);
        }
        
        .sidebar-menu li a i {
            margin-right: 10px;
            font-size: 1.2rem;
        }
        
        .main-content {
            margin-left: var(--sidebar-width);
            padding: 0;
        }
        
        .topbar {
            background: white;
            padding: 15px 30px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .content-wrapper {
            padding: 30px;
        }
        
        .card {
            border: none;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            border-radius: 10px;
            margin-bottom: 30px;
        }
        
        .card-header {
            background: white;
            border-bottom: 2px solid #f1f5f9;
            padding: 20px;
            font-weight: 600;
        }
        
        .stat-card {
            background: linear-gradient(135deg, var(--primary-color) 0%, #6366f1 100%);
            color: white;
            padding: 25px;
            border-radius: 10px;
        }
        
        .stat-card h3 {
            font-size: 2rem;
            margin-bottom: 5px;
        }
        
        .btn-primary {
            background: var(--primary-color);
            border: none;
        }
        
        .btn-primary:hover {
            background: #4338ca;
        }
        
        .badge {
            padding: 6px 12px;
            font-weight: 500;
        }
        
        @media (max-width: 768px) {
            .sidebar {
                left: -var(--sidebar-width);
            }
            
            .sidebar.show {
                left: 0;
            }
            
            .main-content {
                margin-left: 0;
            }
        }
    </style>
    
    @stack('styles')
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="logo">
            <i class="bi bi-cash-coin" style="font-size: 2rem;"></i>
            <h4 class="mt-2">Sistem Penggajian</h4>
            <small>{{ auth()->user()->role }}</small>
        </div>
        
        <ul class="sidebar-menu">
            <li>
                <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i class="bi bi-speedometer2"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            
            @if(auth()->user()->isAdmin())
            <li>
                <a href="{{ route('karyawan.index') }}" class="{{ request()->routeIs('karyawan.*') ? 'active' : '' }}">
                    <i class="bi bi-people"></i>
                    <span>Data Karyawan</span>
                </a>
            </li>
            <li>
                <a href="{{ route('jabatan.index') }}" class="{{ request()->routeIs('jabatan.*') ? 'active' : '' }}">
                    <i class="bi bi-briefcase"></i>
                    <span>Jabatan</span>
                </a>
            </li>
            <li>
                <a href="{{ route('users.index') }}" class="{{ request()->routeIs('users.*') ? 'active' : '' }}">
                    <i class="bi bi-person-gear"></i>
                    <span>Manajemen User</span>
                </a>
            </li>
            @endif
            
            @if(auth()->user()->isAdmin() || auth()->user()->isKeuangan())
            <li>
                <a href="{{ route('absensi.index') }}" class="{{ request()->routeIs('absensi.*') ? 'active' : '' }}">
                    <i class="bi bi-calendar-check"></i>
                    <span>Absensi</span>
                </a>
            </li>
            <li>
                <a href="{{ route('lembur.index') }}" class="{{ request()->routeIs('lembur.*') ? 'active' : '' }}">
                    <i class="bi bi-clock-history"></i>
                    <span>Lembur</span>
                </a>
            </li>
            <li>
                <a href="{{ route('tunjangan.index') }}" class="{{ request()->routeIs('tunjangan.*') ? 'active' : '' }}">
                    <i class="bi bi-plus-circle"></i>
                    <span>Tunjangan</span>
                </a>
            </li>
            <li>
                <a href="{{ route('potongan.index') }}" class="{{ request()->routeIs('potongan.*') ? 'active' : '' }}">
                    <i class="bi bi-dash-circle"></i>
                    <span>Potongan</span>
                </a>
            </li>
            <li>
                <a href="{{ route('gaji.index') }}" class="{{ request()->routeIs('gaji.*') ? 'active' : '' }}">
                    <i class="bi bi-cash-stack"></i>
                    <span>Penggajian</span>
                </a>
            </li>
            <li>
                <a href="{{ route('laporan.index') }}" class="{{ request()->routeIs('laporan.*') ? 'active' : '' }}">
                    <i class="bi bi-file-earmark-text"></i>
                    <span>Laporan</span>
                </a>
            </li>
            @endif
            
            @if(auth()->user()->isKaryawan())
            <li>
                <a href="{{ route('gaji.riwayat') }}" class="{{ request()->routeIs('gaji.riwayat') ? 'active' : '' }}">
                    <i class="bi bi-receipt"></i>
                    <span>Slip Gaji Saya</span>
                </a>
            </li>
            <li>
                <a href="{{ route('absensi.saya') }}" class="{{ request()->routeIs('absensi.saya') ? 'active' : '' }}">
                    <i class="bi bi-calendar-check"></i>
                    <span>Absensi Saya</span>
                </a>
            </li>
            @endif
        </ul>
    </div>
    
    <!-- Main Content -->
    <div class="main-content">
        <!-- Topbar -->
        <div class="topbar">
            <div>
                <button class="btn btn-link d-md-none" id="sidebarToggle">
                    <i class="bi bi-list" style="font-size: 1.5rem;"></i>
                </button>
                <h5 class="mb-0 d-inline">@yield('page-title', 'Dashboard')</h5>
            </div>
            
            <div class="dropdown">
                <button class="btn btn-link dropdown-toggle text-decoration-none" type="button" data-bs-toggle="dropdown">
                    <i class="bi bi-person-circle"></i>
                    {{ auth()->user()->username }}
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item" href="{{ route('profile.edit') }}"><i class="bi bi-person"></i> Profil</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="dropdown-item text-danger">
                                <i class="bi bi-box-arrow-right"></i> Logout
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
        
        <!-- Content -->
        <div class="content-wrapper">
            @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                <i class="bi bi-check-circle"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif
            
            @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show">
                <i class="bi bi-exclamation-triangle"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif
            
            @yield('content')
        </div>
    </div>
    
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
    
    <script>
        // Sidebar toggle for mobile
        document.getElementById('sidebarToggle')?.addEventListener('click', function() {
            document.getElementById('sidebar').classList.toggle('show');
        });
        
        // Initialize DataTables
        $(document).ready(function() {
            $('.datatable').DataTable({
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/id.json'
                }
            });
        });
    </script>
    
    @stack('scripts')
</body>
</html>