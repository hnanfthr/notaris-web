@extends('layout.main')
@section('title', 'Edit Pekerjaan')

@section('content')

<form action="{{ route('archives.update', $archive->uuid) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white py-3">
                    <h6 class="mb-0 fw-bold text-warning"><i class="bi bi-pencil-square me-2"></i>Edit Data Administrasi</h6>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-secondary">Deadline Target</label>
                            <input type="date" name="deadline" class="form-control" value="{{ $archive->deadline }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-secondary">Penanggung Jawab (Staff)</label>
                            <input type="text" name="assigned_to" class="form-control" value="{{ $archive->assigned_to }}">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold text-secondary">Kategori & No. Order</label>
                        <div class="input-group">
                            <input type="text" name="nomor_order" class="form-control" value="{{ $archive->nomor_order }}" placeholder="No. Order">
                            <select name="kategori" id="kategoriSelect" class="form-select bg-light fw-bold text-dark" style="max-width: 150px;">
                                <option value="UMUM" {{ $archive->kategori == 'UMUM' ? 'selected' : '' }}>Umum</option>
                                <option value="KPR" {{ $archive->kategori == 'KPR' ? 'selected' : '' }}>KPR</option>
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label class="form-label small fw-bold text-secondary">Nomor Akta</label>
                            <input type="text" name="nomor_akta" class="form-control" value="{{ $archive->nomor_akta }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold text-secondary">Tanggal Akta</label>
                            <input type="date" name="tanggal_akta" class="form-control" value="{{ $archive->tanggal_akta }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold text-primary">Tanggal Akad</label>
                            <input type="date" name="tanggal_akad" class="form-control border-primary" value="{{ $archive->tanggal_akad }}">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold text-secondary">Judul Akta</label>
                        {{-- HAPUS REQUIRED DI SINI --}}
                        <input type="text" name="judul_akta" class="form-control" value="{{ $archive->judul_akta }}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold text-secondary">Jenis Akta</label>
                        <select name="jenis_akta" class="form-select">
                            @foreach(['PPAT (Tanah)', 'Notaris (Umum)', 'Perbankan', 'Lainnya'] as $j)
                                <option value="{{ $j }}" {{ $archive->jenis_akta == $j ? 'selected' : '' }}>{{ $j }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            @php
                $p1 = $archive->clients->where('pivot.peran_dalam_akta', 'Pihak Pertama')->first();
                if(!$p1 && $archive->clients->count() > 0) $p1 = $archive->clients->first();
                $p2 = $archive->clients->where('pivot.peran_dalam_akta', 'Pihak Kedua')->first();
                if(!$p2 && $archive->clients->count() > 1) $p2 = $archive->clients->skip(1)->first();
            @endphp

            <div class="row g-3">
                <div class="col-md-6">
                    <div class="card shadow-sm border-0 h-100">
                        <div class="card-header bg-light py-2">
                            <h6 class="mb-0 fw-bold small text-dark">Pihak Pertama</h6>
                        </div>
                        <div class="card-body">
                            <div class="mb-2">
                                <label class="small text-muted">Nama Lengkap</label>
                                <input type="text" name="nama_pihak1" class="form-control form-control-sm" 
                                       value="{{ $p1 ? $p1->nama : '' }}">
                            </div>
                            <div class="mb-2">
                                <label class="small text-muted">NIK</label>
                                <input type="number" name="nik_pihak1" class="form-control form-control-sm" 
                                       value="{{ $p1 ? $p1->nik : '' }}">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card shadow-sm border-0 h-100">
                        <div class="card-header bg-light py-2">
                            <h6 class="mb-0 fw-bold small text-dark">Pihak Kedua</h6>
                        </div>
                        <div class="card-body">
                            <div class="mb-2">
                                <label class="small text-muted">Nama Lengkap</label>
                                <input type="text" name="nama_pihak2" class="form-control form-control-sm" 
                                       value="{{ $p2 ? $p2->nama : '' }}">
                            </div>
                            <div class="mb-2">
                                <label class="small text-muted">NIK</label>
                                <input type="number" name="nik_pihak2" class="form-control form-control-sm" 
                                       value="{{ $p2 ? $p2->nik : '' }}">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm border-0 mt-4 border-start border-4 border-warning">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <h6 class="fw-bold mb-1">Update Dokumen Scan (PDF)</h6>
                        <p class="text-muted small mb-0">Upload hanya jika ingin mengganti file lama.</p>
                        @if($archive->file_path)
                            <small class="text-success"><i class="bi bi-check-circle me-1"></i>File saat ini tersimpan.</small>
                        @endif
                    </div>
                    <div>
                        <input type="file" name="file_dokumen" class="form-control" accept="application/pdf">
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow-sm border-0 mb-4 bg-light">
                <div class="card-header bg-transparent py-3 border-0">
                    <h6 class="mb-0 fw-bold text-dark"><i class="bi bi-list-check me-2"></i>Lembar Kontrol</h6>
                </div>
                <div class="card-body">
                    
                    <div class="alert alert-success d-flex align-items-center mb-3 py-2 px-3" id="mbrContainer" 
                         style="display: none;">
                        <input class="form-check-input mt-0 me-2" type="checkbox" name="is_mbr" value="1" id="mbrCheck" 
                               {{ $archive->is_mbr ? 'checked' : '' }}>
                        <label class="form-check-label fw-bold small text-success" for="mbrCheck">
                            MBR
                        </label>
                    </div>

                    <p class="small fw-bold text-dark mb-2">Checklist & Target Dokumen:</p>
                    <div class="d-flex flex-column gap-2 mb-3">
                        @php 
                            $checks = ['AJB','BPHTB','PPH Validasi','Daftar Online','PBB','NTPD','Pengecekan','Daftar Offline'];
                            $savedChecks = is_string($archive->checklist_items) ? json_decode($archive->checklist_items, true) : ($archive->checklist_items ?? []); 
                        @endphp
                        
                        @foreach($checks as $dok)
                            @php
                                $isChecked = false;
                                $savedDeadline = '';
                                
                                // Backward compatibility
                                if (is_array($savedChecks)) {
                                    if (isset($savedChecks[$dok]) && is_array($savedChecks[$dok])) {
                                        $isChecked = isset($savedChecks[$dok]['status']) && $savedChecks[$dok]['status'] == 'Selesai';
                                        $savedDeadline = $savedChecks[$dok]['deadline'] ?? '';
                                    } elseif (in_array($dok, $savedChecks)) {
                                        $isChecked = true;
                                    }
                                }
                            @endphp

                            <div class="d-flex align-items-center justify-content-between p-2 border rounded bg-white border-warning border-opacity-25 shadow-sm">
                                <div class="form-check mb-0 d-flex align-items-center">
                                    <input class="form-check-input border-secondary me-2" type="checkbox" name="checklist[{{ $dok }}][status]" value="Selesai" id="chk_{{ Str::slug($dok) }}" {{ $isChecked ? 'checked' : '' }}>
                                    <label class="form-check-label fw-bold text-dark mb-0" for="chk_{{ Str::slug($dok) }}" style="font-size: 0.75rem; cursor: pointer;">
                                        {{ $dok }}
                                    </label>
                                </div>
                                <div style="width: 125px;">
                                    <input type="date" name="checklist[{{ $dok }}][deadline]" class="form-control form-control-sm bg-light border-0 text-muted" style="font-size: 0.7rem; padding: 4px 8px;" value="{{ $savedDeadline }}">
                                </div>
                            </div>
                        @endforeach
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Catatan Kontrol</label>
                        <textarea name="catatan_kontrol" class="form-control form-control-sm border-warning border-opacity-50" rows="3">{{ $archive->catatan_kontrol }}</textarea>
                    </div>
                </div>
            </div>

            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-warning text-white fw-bold py-2">
                    <i class="bi bi-save-fill me-2"></i> UPDATE PERUBAHAN
                </button>
                <a href="{{ route('archives.index') }}" class="btn btn-light border py-2">Kembali</a>
            </div>
        </div>
    </div>
</form>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const katSelect = document.getElementById('kategoriSelect');
        const mbrBox = document.getElementById('mbrContainer');
        const mbrCheck = document.getElementById('mbrCheck');

        // Fungsi untuk mengecek status
        function toggleMbr() {
            if(katSelect.value === 'KPR') {
                mbrBox.style.display = 'flex';
                mbrBox.classList.add('animate__animated', 'animate__fadeIn');
            } else {
                mbrBox.style.display = 'none';
                mbrCheck.checked = false; // Reset checkbox jika bukan KPR
            }
        }

        // Jalankan saat dropdown berubah
        katSelect.addEventListener('change', toggleMbr);

        // Jalankan sekali saat halaman pertama kali dimuat (untuk inisialisasi status MBR data lama)
        toggleMbr();
    });
</script>

@endsection