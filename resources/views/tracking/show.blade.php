<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tracking Berkas - Notaris Imam Safari</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #f8fafc; }
    </style>
</head>
<body>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            
            <div class="text-center mb-4">
                <h5 class="fw-bold text-primary">NOTARIS IMAM SAFARI, S.H.</h5>
                <p class="text-muted small">Tracking Progress Berkas Real-time</p>
            </div>

            <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
                <div class="card-header bg-primary text-white text-center py-3">
                    <h6 class="mb-0 text-uppercase opacity-75 small">Status Saat Ini</h6>
                    <h3 class="fw-bold my-1">{{ $archive->tahapan }}</h3>
                    <span class="badge bg-white text-primary rounded-pill mt-2">
                        Update: {{ $archive->updated_at ? date('d M Y', strtotime($archive->updated_at)) : '-' }}
                    </span>
                </div>
                <div class="card-body p-4">
                    <div class="mb-3">
                        <label class="small text-muted fw-bold">JUDUL PEKERJAAN</label>
                        <div class="fw-bold text-dark">{{ $archive->judul_akta }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-6">
                            <label class="small text-muted fw-bold">NOMOR ORDER</label>
                            <div class="text-dark">{{ $archive->nomor_order ?? '-' }}</div>
                        </div>
                        <div class="col-6">
                            <label class="small text-muted fw-bold">NOMOR AKTA</label>
                            <div class="text-dark">{{ $archive->nomor_akta ?? 'Belum terbit' }}</div>
                        </div>
                    </div>
                    
                    <div class="border-top pt-3">
                         <label class="small text-muted fw-bold mb-2">PARA PIHAK</label>
                         @forelse($archive->clients as $c)
                            <div class="d-flex align-items-center mb-2">
                                @if($c->pivot->peran_dalam_akta == 'Pihak Kedua')
                                    <i class="bi bi-person-fill text-warning me-2 fs-5"></i>
                                    <div>
                                        <div class="small fw-bold text-dark">{{ $c->nama }}</div>
                                        <div class="small text-muted" style="font-size: 10px;">Pihak Kedua</div>
                                    </div>
                                @else
                                    <i class="bi bi-person-fill text-primary me-2 fs-5"></i>
                                    <div>
                                        <div class="small fw-bold text-dark">{{ $c->nama }}</div>
                                        <div class="small text-muted" style="font-size: 10px;">Pihak Pertama</div>
                                    </div>
                                @endif
                            </div>
                         @empty
                            <div class="small text-muted fst-italic">- Data pihak belum diinput -</div>
                         @endforelse
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-4 text-center">Riwayat Tahapan</h6>
                    
                    @php
                        $steps = ['Pemberkasan', 'Validasi / Cek BPN', 'Tanda Tangan Akta', 'Proses Balik Nama', 'Siap Ambil', 'Selesai'];
                    @endphp

                    <ul class="list-group list-group-flush border-0">
                        @foreach($steps as $step)
                            @php
                                $isActive = ($archive->tahapan == $step);
                                $isPassed = false; 
                            @endphp
                            
                            <li class="list-group-item border-0 d-flex align-items-center px-0">
                                <div class="me-3 d-flex flex-column align-items-center" style="width: 30px;">
                                    @if($isActive)
                                        <i class="bi bi-check-circle-fill text-primary fs-4"></i>
                                    @elseif($archive->status == 'ARCHIVED' || ($step == 'Selesai' && $archive->status == 'ARCHIVED'))
                                        <i class="bi bi-check-circle-fill text-success fs-4"></i>
                                    @else
                                        <i class="bi bi-circle text-muted fs-5"></i>
                                    @endif
                                    @if(!$loop->last)
                                        <div style="width: 2px; height: 30px; background: #e2e8f0; margin-top: 5px;"></div>
                                    @endif
                                </div>
                                <div>
                                    <div class="fw-bold {{ $isActive ? 'text-primary' : 'text-secondary' }}">{{ $step }}</div>
                                    @if($isActive)
                                        <small class="text-primary fw-bold">Sedang dikerjakan</small>
                                    @endif
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>

            <div class="text-center text-muted small mt-4">
                &copy; {{ date('Y') }} Kantor Notaris Imam Safari<br>
                Jl. Pramuka No.105 A, Mpanau, Sigi Biromaru, Kabupaten Sigi
            </div>

        </div>
    </div>
</div>

</body>
</html>