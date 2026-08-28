@extends('layout.main')
@section('title', 'Input Pekerjaan Baru')

@section('content')

<form action="{{ route('archives.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white py-3">
                    <h6 class="mb-0 fw-bold text-primary"><i class="bi bi-journal-text me-2"></i>Data Administrasi</h6>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-secondary">Deadline Target</label>
                            <input type="date" name="deadline" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-secondary">Penanggung Jawab (Staff)</label>
                            <input type="text" name="assigned_to" class="form-control" placeholder="Nama Staff...">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold text-secondary">Kategori & No. Order</label>
                        <div class="input-group">
                            <input type="text" name="nomor_order" class="form-control" placeholder="No. Order (Opsional)">
                            <select name="kategori" id="kategoriSelect" class="form-select bg-light fw-bold text-dark" style="max-width: 150px;">
                                <option value="UMUM">Umum</option>
                                <option value="KPR">KPR</option>
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label class="form-label small fw-bold text-secondary">Nomor Akta</label>
                            <input type="text" name="nomor_akta" class="form-control" placeholder="Contoh: 15/2026">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold text-secondary">Tanggal Akta</label>
                            <input type="date" name="tanggal_akta" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold text-primary">Tanggal Akad</label>
                            <input type="date" name="tanggal_akad" class="form-control border-primary">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold text-secondary">Judul Akta</label>
                        {{-- HAPUS REQUIRED DI SINI --}}
                        <input type="text" name="judul_akta" class="form-control" placeholder="Contoh: Akta Jual Beli (AJB)">
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold text-secondary">Jenis Akta</label>
                        <select name="jenis_akta" class="form-select">
                            <option value="PPAT (Tanah)">PPAT (Tanah)</option>
                            <option value="Notaris (Umum)">Notaris (Umum)</option>
                            <option value="Perbankan">Perbankan</option>
                            <option value="Lainnya">Lainnya</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="row g-3">
                <div class="col-md-6">
                    <div class="card shadow-sm border-0 h-100">
                        <div class="card-header bg-light py-2">
                            <h6 class="mb-0 fw-bold small text-dark"><i class="bi bi-person-fill me-2"></i>Pihak Pertama</h6>
                        </div>
                        <div class="card-body">
                            <div class="mb-2">
                                <label class="small text-muted">Nama Lengkap</label>
                                <input type="text" name="nama_pihak1" class="form-control form-control-sm" placeholder="Nama Pihak 1">
                            </div>
                            <div class="mb-2">
                                <label class="small text-muted">NIK</label>
                                <input type="number" name="nik_pihak1" class="form-control form-control-sm" placeholder="NIK Pihak 1">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card shadow-sm border-0 h-100">
                        <div class="card-header bg-light py-2">
                            <h6 class="mb-0 fw-bold small text-dark"><i class="bi bi-people-fill me-2"></i>Pihak Kedua</h6>
                        </div>
                        <div class="card-body">
                            <div class="mb-2">
                                <label class="small text-muted">Nama Lengkap</label>
                                <input type="text" name="nama_pihak2" class="form-control form-control-sm" placeholder="Nama Pihak 2">
                            </div>
                            <div class="mb-2">
                                <label class="small text-muted">NIK</label>
                                <input type="number" name="nik_pihak2" class="form-control form-control-sm" placeholder="NIK Pihak 2">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm border-0 mt-4 border-start border-4 border-primary">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <h6 class="fw-bold mb-1">Upload Dokumen Scan (PDF)</h6>
                        <p class="text-muted small mb-0">Pastikan format PDF maksimal 5MB.</p>
                    </div>
                    <div>
                        <input type="file" name="file_dokumen" class="form-control" accept="application/pdf">
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow-sm border-0 mb-4 bg-warning bg-opacity-10">
                <div class="card-header bg-warning bg-opacity-25 py-3 border-0">
                    <h6 class="mb-0 fw-bold text-dark"><i class="bi bi-list-check me-2"></i>Lembar Kontrol</h6>
                </div>
                <div class="card-body">
                    
                    <div class="alert alert-success d-flex align-items-center mb-3 py-2 px-3" id="mbrContainer" style="display: none;">
                        <input class="form-check-input mt-0 me-2" type="checkbox" name="is_mbr" value="1" id="mbrCheck">
                        <label class="form-check-label fw-bold small text-success" for="mbrCheck">
                            MBR
                        </label>
                    </div>

                    <p class="small fw-bold text-dark mb-2">Checklist & Target Dokumen:</p>
                    <div class="d-flex flex-column gap-2 mb-3">
                        @php $checks = ['AJB','BPHTB','PPH Validasi','Daftar Online','PBB','NTPD','Pengecekan','Daftar Offline']; @endphp
                        
                        @foreach($checks as $dok)
                        <div class="d-flex align-items-center justify-content-between p-2 border rounded bg-white border-warning border-opacity-25 shadow-sm">
                            <div class="form-check mb-0 d-flex align-items-center">
                                <input class="form-check-input border-secondary me-2" type="checkbox" name="checklist[{{ $dok }}][status]" value="Selesai" id="chk_{{ Str::slug($dok) }}">
                                <label class="form-check-label fw-bold text-dark mb-0" for="chk_{{ Str::slug($dok) }}" style="font-size: 0.75rem; cursor: pointer;">
                                    {{ $dok }}
                                </label>
                            </div>
                            <div style="width: 125px;">
                                <input type="date" name="checklist[{{ $dok }}][deadline]" class="form-control form-control-sm bg-light border-0 text-muted" style="font-size: 0.7rem; padding: 4px 8px;">
                            </div>
                        </div>
                        @endforeach
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Catatan Kontrol (Opsional)</label>
                        <textarea name="catatan_kontrol" class="form-control form-control-sm border-warning border-opacity-50" rows="3" placeholder="Cth: PBB sudah bayar, BPHTB tunggu info..."></textarea>
                    </div>
                </div>
            </div>

            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-primary fw-bold py-2">
                    <i class="bi bi-save-fill me-2"></i> SIMPAN PEKERJAAN
                </button>
                <a href="{{ route('archives.index') }}" class="btn btn-light border py-2">Batal</a>
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
                // Animasi kecil biar smooth
                mbrBox.classList.add('animate__animated', 'animate__fadeIn');
            } else {
                mbrBox.style.display = 'none';
                mbrCheck.checked = false; // Reset checkbox jika bukan KPR
            }
        }

        // Jalankan saat dropdown berubah
        katSelect.addEventListener('change', toggleMbr);

        // Jalankan sekali saat halaman pertama kali dimuat (untuk inisialisasi)
        toggleMbr();
    });
</script>

@endsection