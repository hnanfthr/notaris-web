<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Notaris Modern</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/plugins/monthSelect/style.css">

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #f0f2f5; }
        .sidebar { background: #ffffff; width: 260px; min-height: 100vh; border-right: 1px solid #eef2f6; position: fixed; z-index: 100; }
        .sidebar .brand { padding: 30px 25px; font-weight: 800; font-size: 1.4rem; color: #1e293b; letter-spacing: -0.5px; }
        .sidebar .menu-item { padding: 12px 25px; display: flex; align-items: center; color: #64748b; text-decoration: none; font-weight: 500; transition: 0.3s; margin-bottom: 5px; border-right: 3px solid transparent; }
        .sidebar .menu-item:hover, .sidebar .menu-item.active { color: #2563eb; background: #eff6ff; border-right-color: #2563eb; }
        .sidebar .menu-item i { font-size: 1.2rem; margin-right: 12px; }
        .main-wrapper { margin-left: 260px; padding: 30px; transition: 0.3s; }
        .card { border: none; border-radius: 16px; box-shadow: 0 5px 20px rgba(0,0,0,0.03); background: white; }
        .card-header { background: white; border-bottom: 1px solid #f1f5f9; padding: 20px 25px; border-radius: 16px 16px 0 0 !important; }
        .btn { padding: 10px 20px; border-radius: 10px; font-weight: 600; }
        .btn-circle { width: 35px; height: 35px; padding: 0; display: inline-flex; align-items: center; justify-content: center; border-radius: 50%; }
        .table thead th { font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px; color: #94a3b8; font-weight: 700; padding: 15px 25px; background: #f8fafc; border: none; }
        .table tbody td { padding: 20px 25px; vertical-align: middle; border-bottom: 1px solid #f1f5f9; color: #334155; }
        
        /* Style Notification */
        .dropdown-menu-notify { width: 320px; padding: 0; border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.1); border-radius: 12px; }
        .notify-header { padding: 15px; border-bottom: 1px solid #eee; font-weight: bold; }
        .notify-item { padding: 10px 15px; border-bottom: 1px solid #f5f5f5; display: block; text-decoration: none; color: #333; transition: 0.2s; }
        .notify-item:hover { background: #f9f9f9; }
        .notify-item:last-child { border-bottom: none; }
        
        /* === FIX TAHUN KETUTUPAN === */
        .flatpickr-calendar { 
            border-radius: 12px; 
            box-shadow: 0 10px 40px rgba(0,0,0,0.2) !important; 
            border: none; 
            overflow: hidden; /* Biar sudut bulat rapi */
        }
        
        /* Header Biru diperbesar paddingnya */
        .flatpickr-months {
            background: #2563eb !important;
            padding-top: 25px !important; /* Tambah padding atas biar tahun turun */
            padding-bottom: 10px !important;
            border-radius: 12px 12px 0 0;
            margin-bottom: 5px;
        }

        /* Warna Teks Tahun & Navigasi */
        .flatpickr-current-month,
        .flatpickr-current-month input.cur-year {
            color: #fff !important; 
            font-weight: 800 !important;
            font-size: 1.2rem !important;
        }
        
        /* Panah Kiri Kanan */
        .flatpickr-prev-month, .flatpickr-next-month {
            fill: #fff !important;
            top: 25px !important; /* Sesuaikan posisi panah */
        }
        .flatpickr-prev-month:hover svg, .flatpickr-next-month:hover svg {
            fill: #ddd !important;
        }
        
        /* Bagian Pilihan Bulan */
        .flatpickr-monthSelect-months {
            margin: 5px;
        }
        .flatpickr-monthSelect-month {
            border-radius: 8px;
            font-weight: 600;
        }
        .flatpickr-monthSelect-month.selected {
            background: #2563eb !important;
            border-color: #2563eb !important;
            box-shadow: 0 4px 10px rgba(37, 99, 235, 0.3);
        }
    </style>
</head>
<body>

<div class="sidebar">
    <div class="brand">
        <span class="text-primary">NOTARIS</span>
        MUHAMMAD IMAM SAFARI
    </div>
    
    <div class="mt-2">
        <small class="text-uppercase text-secondary fw-bold px-4 mb-3 d-block" style="font-size: 0.7rem;">Main Menu</small>
        
        <a href="{{ route('archives.index') }}" class="menu-item {{ request()->routeIs('archives.index') ? 'active' : '' }}">
            <i class="bi bi-grid-1x2-fill"></i> Dashboard
        </a>
        <a href="{{ route('archives.create') }}" class="menu-item {{ request()->routeIs('archives.create') ? 'active' : '' }}">
            <i class="bi bi-plus-square-fill"></i> Input Baru
        </a>
        <a href="{{ route('archives.list') }}" class="menu-item {{ request()->routeIs('archives.list') ? 'active' : '' }}">
            <i class="bi bi-archive-fill"></i> Arsip Digital
        </a>
    </div>

    <div class="position-absolute bottom-0 w-100 p-4 border-top">
        <div class="d-flex align-items-center gap-3">
            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; font-weight: bold;">
                {{ substr(Auth::user()->name ?? 'U', 0, 1) }}
            </div>
            
            <div style="line-height: 1.1; width: 100%;">
                <h6 class="m-0 fw-bold text-dark text-truncate" style="font-size: 0.9rem; max-width: 130px;">
                    {{ Auth::user()->name ?? 'User' }}
                </h6>
                <form action="{{ route('logout') }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn p-0 text-danger border-0 small bg-transparent" style="font-size: 0.75rem;">
                        <i class="bi bi-box-arrow-right me-1"></i> Logout
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="main-wrapper">
    <div class="d-flex justify-content-between align-items-center mb-5">
        <div>
            <h4 class="fw-bold text-dark mb-1">@yield('title', 'Sistem Informasi Arsip')</h4>
            <p class="text-muted mb-0">{{ date('l, d F Y') }}</p>
        </div>
        
        <div class="d-flex gap-3 align-items-center">
            
            <form action="{{ route('archives.index') }}" method="GET">
                <div class="input-group">
                    <input type="text" name="search" class="form-control border-end-0 bg-white shadow-sm" placeholder="Cari data..." style="border-radius: 10px 0 0 10px;">
                    <button class="btn btn-white bg-white shadow-sm border border-start-0 text-secondary" type="submit" style="border-radius: 0 10px 10px 0;">
                        <i class="bi bi-search"></i>
                    </button>
                </div>
            </form>

            @php
                // AMBIL SEMUA DATA AKTIF
                $activeTasks = \App\Models\Archive::where('status', 'PROCESS')->get();
                $notifications = [];
                $today = \Carbon\Carbon::today();
                $limitDate = \Carbon\Carbon::now()->addDays(3);

                foreach ($activeTasks as $task) {
                    // 1. Cek Deadline Utama
                    if ($task->deadline) {
                        $mainDeadline = \Carbon\Carbon::parse($task->deadline);
                        if ($mainDeadline->lte($limitDate)) {
                            $diff = $today->diffInDays($mainDeadline, false);
                            $notifications[] = [
                                'judul' => $task->judul_akta,
                                'tipe' => 'Target Utama',
                                'diff' => $diff,
                                'staff' => $task->assigned_to,
                                'url' => route('archives.index') . '?search=' . urlencode($task->nomor_akta)
                            ];
                        }
                    }

                    // 2. Cek Deadline di dalam Checklist/Lembar Kontrol
                    if ($task->checklist_items) {
                        $checks = is_string($task->checklist_items) ? json_decode($task->checklist_items, true) : $task->checklist_items;
                        
                        if (is_array($checks)) {
                            foreach ($checks as $namaDokumen => $dataDokumen) {
                                // Pastikan formatnya array (karena format lama nyimpen string biasa)
                                if (is_array($dataDokumen) && isset($dataDokumen['deadline']) && !empty($dataDokumen['deadline'])) {
                                    
                                    // Cek kalau dokumen belum berstatus Selesai
                                    $isSelesai = isset($dataDokumen['status']) && $dataDokumen['status'] == 'Selesai';
                                    
                                    if (!$isSelesai) {
                                        $dokDeadline = \Carbon\Carbon::parse($dataDokumen['deadline']);
                                        if ($dokDeadline->lte($limitDate)) {
                                            $diff = $today->diffInDays($dokDeadline, false);
                                            $notifications[] = [
                                                'judul' => $task->judul_akta,
                                                'tipe' => "Dok: " . $namaDokumen,
                                                'diff' => $diff,
                                                'staff' => $task->assigned_to,
                                                'url' => route('archives.edit', $task->uuid) // Arahkan ke halaman edit
                                            ];
                                        }
                                    }
                                }
                            }
                        }
                    }
                }

                // Urutkan notifikasi dari yang paling gawat (telat / minus)
                usort($notifications, function($a, $b) {
                    return $a['diff'] <=> $b['diff'];
                });
                
                $notifCount = count($notifications);
            @endphp

            <div class="dropdown">
                <button class="btn btn-white bg-white shadow-sm border position-relative" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-bell-fill text-secondary"></i>
                    @if($notifCount > 0)
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger border border-light">
                            {{ $notifCount }}
                        </span>
                    @endif
                </button>
                <ul class="dropdown-menu dropdown-menu-end dropdown-menu-notify">
                    <li class="notify-header bg-light d-flex justify-content-between">
                        <span>Notifikasi Deadline</span>
                        <span class="badge bg-danger rounded-pill">{{ $notifCount }}</span>
                    </li>
                    <div style="max-height: 350px; overflow-y: auto;">
                        @forelse($notifications as $notif)
                            <a href="{{ $notif['url'] }}" class="notify-item">
                                <div class="d-flex justify-content-between mb-1">
                                    <small class="fw-bold text-truncate" style="max-width: 170px;">{{ $notif['judul'] }}</small>
                                    @if($notif['diff'] < 0)
                                        <span class="badge bg-danger" style="font-size: 8px;">TELAT {{ abs($notif['diff']) }} HR</span>
                                    @elseif($notif['diff'] == 0)
                                        <span class="badge bg-danger" style="font-size: 8px;">HARI INI</span>
                                    @else
                                        <span class="badge bg-warning text-dark" style="font-size: 8px;">H-{{ $notif['diff'] }}</span>
                                    @endif
                                </div>
                                <div class="d-flex justify-content-between align-items-center mt-1">
                                    <span class="badge bg-light text-primary border border-primary border-opacity-25" style="font-size: 0.65rem;">
                                        {{ $notif['tipe'] }}
                                    </span>
                                    <div class="small text-muted" style="font-size: 0.75rem;">
                                        <i class="bi bi-person me-1"></i> {{ $notif['staff'] ?? 'Belum diset' }}
                                    </div>
                                </div>
                            </a>
                        @empty
                            <div class="text-center py-4 text-muted small">
                                <i class="bi bi-check-circle fs-4 d-block mb-2 text-success"></i>
                                Semua aman.
                            </div>
                        @endforelse
                    </div>
                </ul>
            </div>

        </div>
    </div>

    @yield('content')
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://npmcdn.com/flatpickr/dist/l10n/id.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/plugins/monthSelect/index.js"></script>

<script>
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: "{{ session('success') }}",
            timer: 3000,
            showConfirmButton: false,
            background: '#ffffff',
            iconColor: '#2563eb'
        });
    @endif

    function confirmDelete(id) {
        Swal.fire({
            title: 'Hapus Permanen?',
            text: "Data yang dihapus tidak bisa dikembalikan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Ya, Hapus!'
        }).then((result) => {
            if (result.isConfirmed) document.getElementById('delete-form-' + id).submit();
        })
    }

    function confirmComplete(id) {
        Swal.fire({
            title: 'Pekerjaan Selesai?',
            text: "Data akan dipindahkan ke Arsip Digital.",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#22c55e',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Ya, Selesai!'
        }).then((result) => {
            if (result.isConfirmed) document.getElementById('complete-form-' + id).submit();
        })
    }
</script>

</body>
</html>