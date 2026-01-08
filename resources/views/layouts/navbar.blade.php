<nav class="navbar navbar-expand-lg top-navbar">
    <div class="container-fluid">
        <span class="navbar-brand mb-0">
            <i class="bi bi-grid-3x3-gap me-2"></i>
            @yield('page-title', 'Dashboard')
        </span>
        
        <div class="d-flex align-items-center ms-auto">
            <!-- Notifications -->
            <div class="dropdown me-3">
                <button class="btn btn-light position-relative" type="button" data-bs-toggle="dropdown">
                    <i class="bi bi-bell"></i>
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                        3
                        <span class="visually-hidden">unread notifications</span>
                    </span>
                </button>
                <ul class="dropdown-menu dropdown-menu-end shadow">
                    <li>
                        <h6 class="dropdown-header">Notifikasi</h6>
                    </li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <a class="dropdown-item" href="#">
                            <div class="d-flex">
                                <i class="bi bi-info-circle text-primary me-2"></i>
                                <div>
                                    <small class="fw-bold">Pengumuman Sistem</small>
                                    <br>
                                    <small class="text-muted">Update sistem terbaru</small>
                                </div>
                            </div>
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="#">
                            <div class="d-flex">
                                <i class="bi bi-cash text-success me-2"></i>
                                <div>
                                    <small class="fw-bold">Gaji Sudah Ditransfer</small>
                                    <br>
                                    <small class="text-muted">Cek slip gaji Anda</small>
                                </div>
                            </div>
                        </a>
                    </li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <a class="dropdown-item text-center small text-primary" href="#">
                            Lihat Semua Notifikasi
                        </a>
                    </li>
                </ul>
            </div>

            <!-- User Dropdown -->
            <div class="dropdown user-dropdown">
                <button class="btn dropdown-toggle" type="button" data-bs-toggle="dropdown">
                    <i class="bi bi-person-circle me-2"></i>
                    {{ Auth::user()->name }}
                </button>
                <ul class="dropdown-menu dropdown-menu-end shadow">
                    <li>
                        <div class="dropdown-header">
                            <div class="d-flex flex-column">
                                <span class="fw-bold">{{ Auth::user()->name }}</span>
                                <small class="text-muted">{{ Auth::user()->email }}</small>
                                <span class="badge bg-primary mt-2 align-self-start">
                                    {{ ucfirst(Auth::user()->role->name) }}
                                </span>
                            </div>
                        </div>
                    </li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <a class="dropdown-item" href="#">
                            <i class="bi bi-person me-2"></i> Profil Saya
                        </a>
                    </li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="dropdown-item text-danger">
                                <i class="bi bi-box-arrow-right me-2"></i> Logout
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</nav>