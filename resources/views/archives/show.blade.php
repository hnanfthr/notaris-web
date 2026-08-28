@extends('layout.main')
@section('title', 'Detail Pekerjaan')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h5 class="fw-bold mb-1 text-primary"><i class="bi bi-file-earmark-text me-2"></i>Detail Pekerjaan</h5>
        <p class="text-muted small mb-0">No. Order: {{ $archive->nomor_order ?? '-' }} | Dibuat: {{ $archive->created_at->format('d M Y') }}</p>
    </div>
    <div>
        <a href="{{ route('archives.edit', $archive->uuid) }}" class="btn btn-warning text-white fw-bold btn-sm">
            <i class="bi bi-pencil-square me-2"></i> Edit Data
        </a>
        <a href="{{ route('archives.index') }}" class="btn btn-light border btn-sm">Kembali</a>
    </div>
</div>

@php
    // LOGIKA PENGECEKAN DATA KOSONG UNTUK ALERT
    $missingFields = [];
    if(empty($archive->nomor_akta)) $missingFields[] = 'Nomor Akta';
    if(empty($archive->tanggal_akta)) $missingFields[] = 'Tanggal Akta';
    if(empty($archive->file_path)) $missingFields[] = 'Upload Dokumen Scan (PDF)';
    
    $p1 = $archive->clients->where('pivot.peran_dalam_akta', 'Pihak Pertama')->first();
    $p2 = $archive->clients->where('pivot.peran_dalam_akta', 'Pihak Kedua')->first();
    
    if(!$p1) {
        $missingFields[] = 'Nama Pihak Pertama';
    } elseif(empty($p1->nik)) {
        $missingFields[] = 'NIK Pihak Pertama';
    }

    if($p2 && empty($p2->nik)) {
        $missingFields[] = 'NIK Pihak Kedua';
    }
@endphp

{{-- KOTAK ALERT PERINGATAN (Hanya muncul jika ada data kosong) --}}
@if(count($missingFields) > 0)
    <div class="alert alert-warning border-warning shadow-sm mb-4 d-flex align-items-start rounded-4">
        <i class="bi bi-exclamation-triangle-fill fs-3 text-warning me-3 mt-1"></i>
        <div class="w-100">
            <h6 class="fw-bold text-dark mb-1">Peringatan: Ada Data yang Belum Lengkap!</h6>
            <p class="mb-2 small text-dark">Berkas ini masih tersimpan sebagai "Draf / Control". Mohon lengkapi daftar di bawah ini sebelum status pekerjaan diselesaikan:</p>
            <ul class="mb-0 small fw-bold text-danger ps-3">
                @foreach($missingFields as $field)
                    <li>{{ $field }}</li>
                @endforeach
            </ul>
        </div>
        <div class="ms-auto flex-shrink-0">
            <a href="{{ route('archives.edit', $archive->uuid) }}" class="btn btn-warning fw-bold text-white shadow-sm mt-2">
                <i class="bi bi-pencil me-1"></i> Lengkapi Data
            </a>
        </div>
    </div>
@endif

<div class="row g-4">
    <div class="col-lg-8">
        
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-primary bg-opacity-10 py-3">
                <h6 class="mb-0 fw-bold text-primary">Data Administrasi</h6>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="small text-muted d-block">Judul Akta</label>
                        <span class="fw-bold text-dark fs-5">{{ $archive->judul_akta }}</span>
                    </div>
                    <div class="col-md-3">
                        <label class="small text-muted d-block">Nomor Akta</label>
                        <span class="fw-bold text-dark">{{ $archive->nomor_akta ?? 'Belum ada nomor' }}</span>
                    </div>
                    <div class="col-md-3">
                        <label class="small text-muted d-block">Jenis</label>
                        <span class="badge bg-info text-dark">{{ $archive->jenis_akta }}</span>
                    </div>

                    <div class="col-12 border-top my-2"></div>

                    <div class="col-md-4">
                        <label class="small text-muted d-block">Tanggal Akta</label>
                        @if($archive->tanggal_akta)
                            <span class="fw-bold"><i class="bi bi-calendar-event me-1"></i> {{ \Carbon\Carbon::parse($archive->tanggal_akta)->format('d F Y') }}</span>
                        @else
                            <span class="text-muted small fst-italic">- Belum diset -</span>
                        @endif
                    </div>
                    <div class="col-md-4">
                        <label class="small text-muted d-block text-primary">Tanggal Akad</label>
                        @if($archive->tanggal_akad)
                            <span class="fw-bold text-primary"><i class="bi bi-calendar-check me-1"></i> {{ \Carbon\Carbon::parse($archive->tanggal_akad)->format('d F Y') }}</span>
                        @else
                            <span class="text-muted small fst-italic">- Belum diset -</span>
                        @endif
                    </div>
                    <div class="col-md-4">
                        <label class="small text-muted d-block">Deadline Target</label>
                        @if($archive->deadline)
                            <span class="fw-bold text-danger"><i class="bi bi-alarm me-1"></i> {{ \Carbon\Carbon::parse($archive->deadline)->format('d M Y') }}</span>
                        @else
                            <span class="text-muted small fst-italic">- Belum diset -</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-3 mb-4">
            <div class="col-md-6">
                <div class="card shadow-sm border-0 h-100 border-start border-4 border-primary">
                    <div class="card-body">
                        <h6 class="text-primary fw-bold mb-3 small text-uppercase">Pihak Pertama</h6>
                        @if($p1)
                            <h5 class="fw-bold mb-1">{{ $p1->nama }}</h5>
                            <p class="text-muted small mb-0"><i class="bi bi-person-vcard me-1"></i> NIK: {{ $p1->nik ?? '-' }}</p>
                        @else
                            <span class="text-muted small fst-italic">- Data kosong -</span>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card shadow-sm border-0 h-100 border-start border-4 border-warning">
                    <div class="card-body">
                        <h6 class="text-warning fw-bold mb-3 small text-uppercase">Pihak Kedua</h6>
                        @if($p2)
                            <h5 class="fw-bold mb-1">{{ $p2->nama }}</h5>
                            <p class="text-muted small mb-0"><i class="bi bi-person-vcard me-1"></i> NIK: {{ $p2->nik ?? '-' }}</p>
                        @else
                            <span class="text-muted small fst-italic">- Tidak ada Pihak Kedua -</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow-sm border-0 mb-4">
            <div class="card-body d-flex align-items-center justify-content-between">
                <div>
                    <h6 class="fw-bold mb-1">File Dokumen Scan</h6>
                    <p class="text-muted small mb-0">Format PDF tersimpan di server.</p>
                </div>
                <div>
                    @if($archive->file_path)
                        <a href="{{ asset('storage/' . $archive->file_path) }}" target="_blank" class="btn btn-outline-primary btn-sm">
                            <i class="bi bi-download me-2"></i> Download / Lihat
                        </a>
                    @else
                        <span class="badge bg-light text-secondary border px-3 py-2">Belum ada file diupload</span>
                    @endif
                </div>
            </div>
        </div>

    </div>

    <div class="col-lg-4">
        
        <div class="card shadow-sm border-0 mb-4 bg-light">
            <div class="card-body">
                <label class="small fw-bold text-muted text-uppercase mb-2">Status Pekerjaan</label>
                <div class="d-grid">
                    @if($archive->status == 'PROCESS')
                        <button class="btn btn-warning text-white fw-bold mb-2">
                            <i class="bi bi-gear-wide-connected me-2"></i> PROSES BERJALAN
                        </button>
                    @else
                        <button class="btn btn-success text-white fw-bold mb-2">
                            <i class="bi bi-check-circle-fill me-2"></i> SELESAI / ARSIP
                        </button>
                    @endif
                </div>
                
                <div class="mt-3">
                    <label class="small text-muted">Tahapan Saat Ini:</label>
                    <div class="progress mt-1" style="height: 10px;">
                        @php
                            $persen = 20;
                            if($archive->tahapan == 'Validasi / Cek BPN') $persen = 40;
                            if($archive->tahapan == 'Tanda Tangan Akta') $persen = 60;
                            if($archive->tahapan == 'Proses Balik Nama') $persen = 80;
                            if($archive->tahapan == 'Siap Ambil' || $archive->status == 'ARCHIVED') $persen = 100;
                        @endphp
                        <div class="progress-bar bg-success" style="width: {{ $persen }}%"></div>
                    </div>
                    <small class="fw-bold text-success d-block mt-1 text-end">{{ $archive->tahapan }}</small>
                </div>
            </div>
        </div>

        <div class="card shadow-sm border-0 text-center">
            <div class="card-body">
                <h6 class="fw-bold text-muted small mb-3">QR TRACKING KLIEN</h6>
                <div class="bg-white p-3 d-inline-block border rounded mb-3">
                    {!! \SimpleSoftwareIO\QrCode\Facades\QrCode::size(120)->generate(route('client.tracking', $archive->uuid)) !!}
                </div>
                <p class="small text-muted mb-3">Scan QR ini untuk melihat progress pekerjaan secara real-time.</p>
                <a href="{{ route('client.tracking', $archive->uuid) }}" target="_blank" class="btn btn-outline-primary btn-sm w-100">
                    <i class="bi bi-link-45deg me-1"></i> Buka Link Tracking
                </a>
            </div>
        </div>

    </div>
</div>

@endsection