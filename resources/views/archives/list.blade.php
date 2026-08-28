@extends('layout.main')
@section('title', 'Arsip Digital')

@section('content')

<div class="row g-4">
    <div class="col-md-3">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3">
                <h6 class="mb-0 fw-bold text-dark"><i class="bi bi-folder-fill text-warning me-2"></i>Filter Periode</h6>
            </div>
            <div class="list-group list-group-flush">
                <a href="{{ route('archives.list') }}" 
                   class="list-group-item list-group-item-action d-flex justify-content-between align-items-center {{ !request('month') ? 'active fw-bold' : '' }}">
                    <span><i class="bi bi-grid-fill me-2"></i>Semua Data</span>
                </a>

                @foreach($monthList as $m)
                    @php
                        // Pelindung kalau datanya kosong/null
                        if($m->year && $m->month) {
                            $dateObj   = \Carbon\Carbon::createFromDate($m->year, $m->month, 1);
                            $monthName = $dateObj->translatedFormat('F Y');
                        } else {
                            $monthName = "Tanpa Tanggal";
                        }
                        $isActive  = request('month') == $m->month && request('year') == $m->year;
                    @endphp

                    <a href="{{ route('archives.list', ['month' => $m->month, 'year' => $m->year]) }}" 
                       class="list-group-item list-group-item-action d-flex justify-content-between align-items-center {{ $isActive ? 'active fw-bold' : '' }}">
                        <span>
                            <i class="bi {{ $isActive ? 'bi-folder2-open' : 'bi-folder' }} me-2"></i>
                            {{ $monthName }}
                        </span>
                        <span class="badge {{ $isActive ? 'bg-white text-primary' : 'bg-light text-secondary' }} rounded-pill">
                            {{ $m->total }}
                        </span>
                    </a>
                @endforeach
            </div>
        </div>
    </div>

    <div class="col-md-9">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3">
                
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="d-flex align-items-center">
                        @if(request('month') && request('month') != '')
                            <h6 class="mb-0 fw-bold text-primary">
                                <i class="bi bi-folder2-open me-2"></i>Arsip {{ \Carbon\Carbon::createFromDate(request('year'), request('month'), 1)->translatedFormat('F Y') }}
                            </h6>
                        @else
                            <h6 class="mb-0 fw-bold">Semua Arsip Tersimpan</h6>
                        @endif
                    </div>
                    
                    <button type="button" class="btn btn-success btn-sm shadow-sm" data-bs-toggle="modal" data-bs-target="#exportModal">
                        <i class="bi bi-file-earmark-excel-fill me-2"></i>Export Laporan
                    </button>
                </div>

                <form action="{{ route('archives.list') }}" method="GET">
                    @if(request('month'))
                        <input type="hidden" name="month" value="{{ request('month') }}">
                        <input type="hidden" name="year" value="{{ request('year') }}">
                    @endif
                    <div class="input-group">
                        <input type="text" name="search" class="form-control form-control-sm border-end-0 bg-light" 
                               placeholder="Cari nomor, judul, atau klien..." value="{{ request('search') }}">
                        <button type="submit" class="input-group-text bg-light border-start-0">
                            <i class="bi bi-search"></i>
                        </button>
                    </div>
                </form>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4">Detail Akta</th>
                            <th>Para Pihak</th>
                            <th>File</th>
                            <th class="text-end pe-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($archives as $archive)
                            <tr>
                                <td class="ps-4">
                                    <div class="d-flex align-items-center mb-1">
                                        @if($archive->kategori == 'KPR')
                                            <span class="badge bg-success me-2" style="font-size: 8px;">KPR</span>
                                        @endif
                                        <span class="fw-bold text-dark">{{ $archive->judul_akta }}</span>
                                    </div>
                                    <div class="small text-muted">
                                        <span class="me-2"><i class="bi bi-upc-scan me-1"></i>{{ $archive->nomor_akta ?? 'Belum ada nomor' }}</span>
                                        <span>
                                            <i class="bi bi-calendar me-1"></i>
                                            {{ $archive->tanggal_akta ? date('d/m/Y', strtotime($archive->tanggal_akta)) : 'Belum diset' }}
                                        </span>
                                    </div>
                                </td>
                                
                                <td>
                                    @foreach($archive->clients as $client)
                                        <div class="d-flex align-items-center mb-1">
                                            @if($client->pivot->peran_dalam_akta == 'Pihak Kedua')
                                                <i class="bi bi-person-fill text-warning me-2" style="font-size: 0.8rem;" title="Pihak Kedua"></i>
                                                <span class="small text-secondary">{{ $client->nama }}</span>
                                            @else
                                                <i class="bi bi-person-fill text-primary me-2" style="font-size: 0.8rem;" title="Pihak Pertama"></i>
                                                <span class="small fw-bold text-dark">{{ $client->nama }}</span>
                                            @endif
                                        </div>
                                    @endforeach
                                </td>

                                <td>
                                    @if($archive->file_path)
                                        <a href="{{ asset('storage/' . $archive->file_path) }}" target="_blank" class="badge bg-primary bg-opacity-10 text-primary text-decoration-none border border-primary px-2 py-1">
                                            <i class="bi bi-file-earmark-pdf-fill me-1"></i> PDF
                                        </a>
                                    @else
                                        <span class="badge bg-light text-secondary border px-2 py-1">Fisik</span>
                                    @endif
                                </td>
                                <td class="text-end pe-4">
                                    <div class="d-flex justify-content-end gap-1">
                                        <a href="{{ route('archives.show', $archive->uuid) }}" class="btn btn-circle btn-white border shadow-sm text-primary" title="Lihat Detail">
                                            <i class="bi bi-eye"></i>
                                        </a>

                                        <form action="{{ route('archives.restore', $archive->id) }}" method="POST" class="d-inline">
                                            @csrf @method('PUT')
                                            <button type="submit" class="btn btn-circle btn-white border shadow-sm text-warning" title="Kembalikan ke Dashboard Aktif">
                                                <i class="bi bi-arrow-counterclockwise"></i>
                                            </button>
                                        </form>
                                        
                                        <button onclick="confirmDelete({{ $archive->id }})" class="btn btn-circle btn-white border shadow-sm text-danger" title="Hapus Permanen">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                        <form id="delete-form-{{ $archive->id }}" action="{{ route('archives.destroy', $archive->id) }}" method="POST" style="display: none;">
                                            @csrf @method('DELETE')
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-5 text-muted">
                                    <div class="mb-2"><i class="bi bi-folder-x fs-1 opacity-25"></i></div>
                                    @if(request('month'))
                                        Tidak ada arsip di bulan ini.
                                    @else
                                        Belum ada arsip tersimpan.
                                    @endif
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="exportModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold"><i class="bi bi-file-earmark-spreadsheet me-2"></i>Download Laporan Excel</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('archives.export') }}" method="GET">
                <div class="modal-body">
                    <div class="alert alert-info py-2 small mb-3">
                        <i class="bi bi-info-circle me-1"></i> Filter bersifat opsional. Kosongkan jika ingin semua data.
                    </div>
                    
                    <h6 class="small fw-bold text-muted mb-2">1. Periode Tanggal</h6>
                    <div class="row g-3 mb-3">
                        <div class="col-6">
                            <label class="form-label small">Dari Bulan</label>
                            <input type="text" name="start_month" class="form-control flatpickr-month bg-white" 
                                   placeholder="Pilih Bulan..." value="{{ date('Y-m', strtotime('-1 month')) }}">
                        </div>
                        <div class="col-6">
                            <label class="form-label small">Sampai Bulan</label>
                            <input type="text" name="end_month" class="form-control flatpickr-month bg-white" 
                                   placeholder="Pilih Bulan..." value="{{ date('Y-m') }}">
                        </div>
                    </div>

                    <h6 class="small fw-bold text-muted mb-2 border-top pt-3">2. Filter Nama Klien (Opsional)</h6>
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label small">Pihak Pertama (Penjual/Developer)</label>
                            <input type="text" name="nama_pihak1" class="form-control" placeholder="Cth: PT. Developer...">
                        </div>
                        <div class="col-12">
                            <label class="form-label small">Pihak Kedua (Pembeli/Debitur)</label>
                            <input type="text" name="nama_pihak2" class="form-control" placeholder="Cth: Nama nasabah...">
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success fw-bold">
                        <i class="bi bi-download me-1"></i> Download Sekarang
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        flatpickr(".flatpickr-month", {
            plugins: [
                new monthSelectPlugin({
                  shorthand: true,
                  dateFormat: "Y-m",
                  altFormat: "F Y",
                  theme: "light"
                })
            ],
            locale: "id",
            allowInput: true,
            altInput: true
        });
    });
</script>

@endsection