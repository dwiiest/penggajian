<div class="sidebar">
    <!-- Logo Section -->
    <div class="logo-section">
        <div class="d-flex align-items-center">
            <div class="logo-icon">
                <i class="bi bi-wallet2"></i>
            </div>
            <div class="ms-3">
                <h5 class="mb-0 fw-bold text-white">E-Payroll</h5>
                <small class="text-white-50">Sistem Penggajian</small>
            </div>
        </div>
    </div>

    <!-- Navigation Menu -->
    <div class="p-3">
        @php
            $role = Auth::user()->role->name;
        @endphp

        <ul class="nav nav-pills flex-column">
            <li class="nav-item">
                <a href="{{ route($role.'.dashboard') }}" class="nav-link {{ request()->routeIs($role.'.dashboard') ? 'active' : '' }}">
                    <i class="bi bi-speedometer2"></i> Dashboard
                </a>
            </li>

            @if(Auth::user()->user_role_id == 1)
                <!-- Admin Menu -->
                <div class="section-title">MASTER DATA</div>
                <li>
                    <a href="{{ route('admin.user-roles.index') }}" 
                    class="nav-link {{ request()->routeIs('admin.user-roles.*') ? 'active' : '' }}">
                        <i class="bi bi-shield-check"></i> Data Role
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.users.index') }}" 
                    class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                        <i class="bi bi-people"></i> Data User
                    </a>
                </li>
                <li>
                    <a href="{{ route('departments.index') }}" class="nav-link">
                        <i class="bi bi-building {{ request()->routeIs('departments.*') ? 'active' : '' }}"></i> Data Departemen
                    </a>
                </li>
                <li>
                    <a href="{{ route('positions.index') }}" 
                    class="nav-link {{ request()->routeIs('positions.*') ? 'active' : '' }}">
                        <i class="bi bi-briefcase"></i> Data Jabatan
                    </a>
                </li>
            @endif

            @if(Auth::user()->user_role_id == 2)
                <!-- HRD Menu -->
                <div class="section-title">KEPEGAWAIAN</div>
                <li>
                    <a href="{{ route('hrd.employees.index') }}" 
                       class="nav-link {{ request()->routeIs('hrd.employees.*') ? 'active' : '' }}">
                        <i class="bi bi-person-badge"></i> Data Karyawan
                    </a>
                </li>
                <li>
                    <a href="{{ route('departments.index') }}" class="nav-link">
                        <i class="bi bi-building {{ request()->routeIs('departments.*') ? 'active' : '' }}"></i> Data Departemen
                    </a>
                </li>
                <li>
                    <a href="{{ route('positions.index') }}" 
                    class="nav-link {{ request()->routeIs('positions.*') ? 'active' : '' }}">
                        <i class="bi bi-briefcase"></i> Data Jabatan
                    </a>
                </li>
                
                <div class="section-title">ABSENSI & LEMBUR</div>
                <li>
                    <a href="{{ route('hrd.attendances.index') }}" 
                       class="nav-link {{ request()->routeIs('hrd.attendances.*') ? 'active' : '' }}">
                        <i class="bi bi-calendar-check"></i> Data Absensi
                    </a>
                </li>
                <li>
                    <a href="{{ route('hrd.overtimes.index') }}" 
                       class="nav-link {{ request()->routeIs('hrd.overtimes.*') && !request()->routeIs('hrd.overtimes.report') ? 'active' : '' }}">
                        <i class="bi bi-clock-history"></i> Data Lembur
                    </a>
                </li>
                
                <div class="section-title">LAPORAN</div>
                <li>
                    <a href="{{ route('hrd.reports.attendance') }}" 
                       class="nav-link {{ request()->routeIs('hrd.reports.*') ? 'active' : '' }}">
                        <i class="bi bi-file-earmark-text"></i> Laporan Absensi
                    </a>
                </li>
                <li>
                    <a href="{{ route('hrd.overtimes.report') }}" 
                       class="nav-link {{ request()->routeIs('hrd.overtimes.report') ? 'active' : '' }}">
                        <i class="bi bi-file-earmark-bar-graph"></i> Laporan Lembur
                    </a>
                </li>
            @endif

            @if(Auth::user()->user_role_id == 3)
                <!-- Finance Menu -->
                <div class="section-title">KEUANGAN</div>
                <li>
                    <a href="#" class="nav-link">
                        <i class="bi bi-cash-stack"></i> Penggajian
                    </a>
                </li>
                <li>
                    <a href="#" class="nav-link">
                        <i class="bi bi-calculator"></i> Tunjangan
                    </a>
                </li>
                <li>
                    <a href="#" class="nav-link">
                        <i class="bi bi-dash-circle"></i> Potongan
                    </a>
                </li>
                
                <div class="section-title">LAPORAN</div>
                <li>
                    <a href="#" class="nav-link">
                        <i class="bi bi-file-earmark-spreadsheet"></i> Laporan Gaji
                    </a>
                </li>
                <li>
                    <a href="#" class="nav-link">
                        <i class="bi bi-graph-up"></i> Analisis Keuangan
                    </a>
                </li>
            @endif

            @if(Auth::user()->user_role_id == 4)
                <!-- Manager Menu -->
                <div class="section-title">MANAJEMEN</div>
                <li>
                    <a href="#" class="nav-link">
                        <i class="bi bi-people"></i> Tim Saya
                    </a>
                </li>
                {{-- <li>
                    <a href="#" class="nav-link">
                        <i class="bi bi-clipboard-check"></i> Persetujuan Absensi
                    </a>
                </li> --}}
                {{-- <li>
                    <a href="#" class="nav-link">
                        <i class="bi bi-graph-up-arrow"></i> Performa Tim
                    </a>
                </li>
                
                <div class="section-title">LAPORAN</div>
                <li>
                    <a href="#" class="nav-link">
                        <i class="bi bi-file-earmark-bar-graph"></i> Laporan Tim
                    </a>
                </li> --}}
            @endif

            @if(Auth::user()->user_role_id == 5)
                <!-- Karyawan Menu -->
                <div class="section-title">MENU SAYA</div>
                <li>
                    <a href="#" class="nav-link">
                        <i class="bi bi-person-circle"></i> Profil Saya
                    </a>
                </li>
                <li>
                    <a href="#" class="nav-link">
                        <i class="bi bi-fingerprint"></i> Absensi
                    </a>
                </li>
                <li>
                    <a href="#" class="nav-link">
                        <i class="bi bi-calendar2-week"></i> Riwayat Absensi
                    </a>
                </li>
                <li>
                    <a href="#" class="nav-link">
                        <i class="bi bi-receipt"></i> Slip Gaji
                    </a>
                </li>
                <li>
                    <a href="#" class="nav-link">
                        <i class="bi bi-clock-history"></i> Cuti & Izin
                    </a>
                </li>
            @endif
        </ul>
    </div>

    <!-- Footer -->
    <div class="mt-auto sidebar-footer">
        <div class="text-center">
            <small>&copy; {{ date('Y') }} E-Payroll System</small><br>
            <small>Made with 
                <i class="bi bi-heart-fill text-pink"></i>
            </small>
        </div>
    </div>
</div>