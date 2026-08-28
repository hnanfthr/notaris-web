@extends('layout.main')
@section('title', 'Dashboard Monitoring')

@section('content')

<div class="row mb-5 g-4">
    <div class="col-md-4">
        <div class="card p-4 h-100 d-flex flex-row align-items-center justify-content-between">
            <div>
                <p class="text-muted mb-1 small fw-bold text-uppercase">Sedang Berjalan</p>
                <h2 class="mb-0 fw-bold text-dark">{{ $ongoing->count() }}</h2>
            </div>
            <div class="bg-warning bg-opacity-10 text-warning p-3 rounded-circle">
                <i class="bi bi-hourglass-split fs-4"></i>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header d-flex flex-wrap justify-content-between align-items-center gap-2">
        <h6 class="mb-0 fw-bold">Pekerjaan Aktif</h6>
        
        <div class="d-flex gap-2" style="min-width: 320px;">
            {{-- TOMBOL FILTER AJAIB --}}
            <a href="{{ route('archives.index', ['filter' => request('filter') == 'incomplete' ? '' : 'incomplete', 'search' => request('search')]) }}" 
               class="btn btn-sm {{ request('filter') == 'incomplete' ? 'btn-danger shadow-sm' : 'btn-outline-danger' }} d-flex align-items-center fw-bold text-nowrap" title="Filter Berkas Belum Lengkap">
                <i class="bi bi-funnel-fill me-1"></i> {{ request('filter') == 'incomplete' ? 'Tampil Semua' : 'Belum Lengkap' }}
            </a>

            <form action="{{ route('archives.index') }}" method="GET" class="flex-grow-1">
                @if(request('filter'))
                    <input type="hidden" name="filter" value="{{ request('filter') }}">
                @endif
                <div class="input-group">
                    <input type="text" name="search" class="form-control form-control-sm border-end-0 bg-light" 
                           placeholder="Cari akta, staff..." value="{{ request('search') }}">
                    <button type="submit" class="input-group-text bg-light border-start-0">
                        <i class="bi bi-search"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>Detail Pekerjaan</th>
                    <th>Staff</th>
                    <th>Target</th>
                    <th width="20%">Tahapan (Timeline)</th>
                    <th class="text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($ongoing as $task)
                    @php
                        // LOGIKA DETEKTOR DATA KOSONG
                        $isIncomplete = false;
                        if(empty($task->nomor_akta) || empty($task->tanggal_akta) || empty($task->file_path)) {
                            $isIncomplete = true;
                        }
                    @endphp
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-2 mb-1">
                                @if($task->kategori == 'KPR')
                                    <span class="badge bg-success" style="font-size: 9px;">KPR</span>
                                @else
                                    <span class="badge bg-primary" style="font-size: 9px;">UMUM</span>
                                @endif
                                <span class="small fw-bold text-secondary">{{ $task->nomor_order ?? '-' }}</span>
                                
                                {{-- LABEL PERINGATAN MUNCUL DI SINI --}}
                                @if($isIncomplete)
                                    <span class="badge bg-danger bg-opacity-10 text-danger border border-danger ms-1" style="font-size: 9px;" title="Ada data yang belum diisi!">
                                        <i class="bi bi-exclamation-circle-fill me-1"></i>BELUM LENGKAP
                                    </span>
                                @endif
                            </div>
                            <div class="fw-bold text-dark">{{ $task->judul_akta }}</div>
                            <small class="text-muted d-block mb-2">No: {{ $task->nomor_akta ?? 'Belum ada nomor' }}</small>
                            
                            @foreach($task->clients as $c)
                                <div class="d-flex align-items-center mb-1">
                                    @if($c->pivot->peran_dalam_akta == 'Pihak Kedua')
                                        <i class="bi bi-person-fill text-warning me-2" style="font-size: 0.8rem;" title="Pihak Kedua"></i>
                                        <span class="small fw-bold text-secondary">{{ $c->nama }}</span>
                                    @else
                                        <i class="bi bi-person-fill text-primary me-2" style="font-size: 0.8rem;" title="Pihak Pertama"></i>
                                        <span class="small fw-bold text-dark">{{ $c->nama }}</span>
                                    @endif
                                </div>
                            @endforeach
                        </td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="bg-light text-primary fw-bold rounded-circle d-flex align-items-center justify-content-center" style="width:30px; height:30px; font-size:10px;">
                                    {{ $task->assigned_to ? substr($task->assigned_to, 0, 1) : '?' }}
                                </div>
                                <span class="small fw-bold">{{ $task->assigned_to ?? 'Belum diset' }}</span>
                            </div>
                        </td>
                        
                        <td>
                            @if($task->deadline)
                                @php
                                    $deadline = \Carbon\Carbon::parse($task->deadline);
                                    $today = \Carbon\Carbon::today();
                                    $diff = $today->diffInDays($deadline, false);
                                @endphp

                                @if($diff < 0)
                                    <span class="badge bg-danger bg-opacity-10 text-danger px-2 py-1 rounded-pill fw-bold" style="font-size: 0.7rem;">
                                        <i class="bi bi-exclamation-triangle-fill me-1"></i> Telat {{ abs($diff) }} Hari
                                    </span>
                                @elseif($diff == 0)
                                    <span class="badge bg-danger px-2 py-1 rounded-pill fw-bold shadow-sm" style="font-size: 0.7rem;">
                                        <i class="bi bi-alarm-fill me-1"></i> HARI INI!
                                    </span>
                                @elseif($diff <= 3)
                                    <span class="badge bg-warning text-dark px-2 py-1 rounded-pill fw-bold border border-warning" style="font-size: 0.7rem;">
                                        Sisa {{ $diff }} Hari
                                    </span>
                                @else
                                    <span class="badge bg-info bg-opacity-10 text-info px-2 py-1 rounded-pill border border-info" style="font-size: 0.7rem;">
                                        {{ $deadline->format('d M') }}
                                    </span>
                                @endif
                            @else
                                <span class="badge bg-light text-secondary border px-2 py-1 rounded-pill fw-bold" style="font-size: 0.7rem;">
                                    Belum diset
                                </span>
                            @endif
                        </td>

                        <td>
                            <form action="{{ route('archives.updateTahapan', $task->id) }}" method="POST">
                                @csrf @method('PUT')
                                <select name="tahapan" class="form-select form-select-sm border-0 bg-light fw-bold text-primary shadow-sm" 
                                        onchange="this.form.submit()" style="cursor: pointer;">
                                    <option value="Pemberkasan" {{ $task->tahapan == 'Pemberkasan' ? 'selected' : '' }}>1. Pemberkasan</option>
                                    <option value="Validasi / Cek BPN" {{ $task->tahapan == 'Validasi / Cek BPN' ? 'selected' : '' }}>2. Cek BPN / Pajak</option>
                                    <option value="Tanda Tangan Akta" {{ $task->tahapan == 'Tanda Tangan Akta' ? 'selected' : '' }}>3. Tanda Tangan</option>
                                    <option value="Proses Balik Nama" {{ $task->tahapan == 'Proses Balik Nama' ? 'selected' : '' }}>4. Proses Balik Nama</option>
                                    <option value="Siap Ambil" {{ $task->tahapan == 'Siap Ambil' ? 'selected' : '' }}>5. Siap Ambil</option>
                                </select>
                            </form>
                        </td>

                        <td class="text-end">
                            <div class="d-flex justify-content-end gap-1">
                                <button type="button" class="btn btn-circle btn-white border shadow-sm text-dark me-1" 
                                        data-bs-toggle="modal" data-bs-target="#qrModal{{ $task->id }}" title="Lihat QR Code">
                                    <i class="bi bi-qr-code"></i>
                                </button>
                                
                                <button onclick="confirmComplete({{ $task->id }})" class="btn btn-circle btn-success text-white shadow-sm" title="Selesai">
                                    <i class="bi bi-check-lg"></i>
                                </button>
                                <form id="complete-form-{{ $task->id }}" action="{{ route('archives.complete', $task->id) }}" method="POST" style="display: none;">
                                    @csrf @method('PUT')
                                </form>

                                <a href="{{ route('archives.show', $task->uuid) }}" class="btn btn-circle btn-white border shadow-sm text-primary"><i class="bi bi-eye"></i></a>
                                <a href="{{ route('archives.edit', $task->uuid) }}" class="btn btn-circle btn-white border shadow-sm text-warning"><i class="bi bi-pencil-fill"></i></a>
                                
                                <button onclick="confirmDelete({{ $task->id }})" class="btn btn-circle btn-white border shadow-sm text-danger" title="Hapus">
                                    <i class="bi bi-trash"></i>
                                </button>
                                <form id="delete-form-{{ $task->id }}" action="{{ route('archives.destroy', $task->id) }}" method="POST" style="display: none;">
                                    @csrf @method('DELETE')
                                </form>
                            </div>

                            <div class="modal fade text-start" id="qrModal{{ $task->id }}" tabindex="-1">
                                <div class="modal-dialog modal-sm modal-dialog-centered">
                                    <div class="modal-content text-center">
                                        <div class="modal-header border-0 pb-0">
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body pb-4">
                                            <h6 class="fw-bold mb-3">Scan untuk Tracking</h6>
                                            
                                            <div class="d-flex justify-content-center mb-3">
                                                {!! \SimpleSoftwareIO\QrCode\Facades\QrCode::size(150)->generate(route('client.tracking', $task->uuid)) !!}
                                            </div>
                                            
                                            <p class="small text-muted mb-3">{{ $task->judul_akta }}<br>{{ $task->clients->first()->nama ?? 'Klien' }}</p>
                                            
                                            <a href="{{ route('client.tracking', $task->uuid) }}" target="_blank" class="btn btn-sm btn-outline-primary w-100 mb-2">
                                                <i class="bi bi-link-45deg me-1"></i> Buka Link
                                            </a>
                                            <button class="btn btn-sm btn-primary w-100" onclick="window.print()">
                                                <i class="bi bi-printer me-1"></i> Print Halaman
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-5 text-muted">
                            @if(request('filter') == 'incomplete')
                                <i class="bi bi-check-circle text-success fs-1 d-block mb-2"></i>
                                Hore! Semua data pekerjaan aktif sudah lengkap.
                            @elseif(request('search'))
                                Tidak ditemukan data dengan kata kunci "<strong>{{ request('search') }}</strong>".
                            @else
                                Belum ada pekerjaan aktif.
                            @endif
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<style>
@media print {
    body * { visibility: hidden; }
    .modal.show, .modal.show * { visibility: visible !important; }
    .modal.show { position: absolute !important; left: 0 !important; top: 0 !important; margin: 0 !important; padding: 0 !important; width: 100% !important; height: auto !important; background: white !important; display: flex !important; align-items: center !important; justify-content: center !important; }
    .modal.show .modal-dialog { width: 100% !important; max-width: 100% !important; margin: 0 !important; box-shadow: none !important; border: 1px solid #ddd !important; }
    .modal.show .modal-content { border: none !important; }
    .btn, .btn-close, .modal-footer { display: none !important; }
    .modal-body { padding: 20px !important; text-align: center; }
}
</style>

@endsection