@php
    use Illuminate\Support\Facades\Route;
    
    $currentRouteName = Route::currentRouteName();
    
    if (!$currentRouteName) return;

    $segments = explode('.', $currentRouteName);
    
    if (end($segments) == 'index') {
        array_pop($segments);
    }

    if (end($segments) == 'dashboard') {
        array_pop($segments);
    }

    $labels = [
        // Roles / Dashboards (Label Utamanya)
        'admin'     => 'Dashboard',
        'hrd'       => 'Dashboard',
        'finance'   => 'Dashboard',
        'manager'   => 'Dashboard',
        'karyawan'  => 'Dashboard',
        
        // Resources (Menu)
        'users'         => 'Users',
        'user-roles'    => 'User Role',
        'departments'   => 'Departemen',
        'positions'     => 'Jabatan',
        'employees'     => 'Karyawan',
        'reports'       => 'Laporan',
        'attendances'   => 'Absensi',
        'overtimes'     => 'Lembur',
        'reports/attendance' => 'Laporan Lembur',
        
        // Actions
        'create'        => 'Tambah Baru',
        'edit'          => 'Edit Data',
        'show'          => 'Detail',
        'attendance'    => 'Absensi',
        'approve'       => 'Menyetujui',
        'reject'        => 'Menolak',
    ];

    $breadcrumbs = [];
    $accumulatedRoute = '';
    
    $totalSegments = count($segments);

    foreach ($segments as $index => $segment) {
        
        $label = $labels[$segment] ?? ucwords(str_replace('-', ' ', $segment));

        $accumulatedRoute .= ($index === 0) ? $segment : '.' . $segment;

        $isLast = ($index === $totalSegments - 1);
        
        $url = null;
        
        if (!$isLast) {
            if (Route::has($accumulatedRoute . '.index')) {
                $url = route($accumulatedRoute . '.index');
            } elseif (Route::has($accumulatedRoute . '.dashboard')) {
                $url = route($accumulatedRoute . '.dashboard');
            } elseif (Route::has($accumulatedRoute)) {
                $url = route($accumulatedRoute);
            }
        }

        $breadcrumbs[] = [
            'label' => $label,
            'url'   => $url,
        ];
    }
@endphp

@if(count($breadcrumbs) > 0)
    <nav aria-label="breadcrumb" class="my-3">
        <ol class="breadcrumb">
            @foreach($breadcrumbs as $breadcrumb)
                @if($breadcrumb['url'])
                    <li class="breadcrumb-item">
                        <a href="{{ $breadcrumb['url'] }}" class="text-decoration-none">{{ $breadcrumb['label'] }}</a>
                    </li>
                @else
                    {{-- Halaman aktif (tanpa link) --}}
                    <li class="breadcrumb-item active" aria-current="page">{{ $breadcrumb['label'] }}</li>
                @endif
            @endforeach
        </ol>
    </nav>
@endif