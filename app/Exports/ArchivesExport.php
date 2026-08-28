<?php

namespace App\Exports;

use App\Models\Archive;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Carbon\Carbon;

class ArchivesExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    protected $start;
    protected $end;
    protected $p1;
    protected $p2;

    // Terima data dari Controller
    public function __construct($start, $end, $p1 = null, $p2 = null)
    {
        $this->start = $start;
        $this->end   = $end;
        $this->p1    = $p1;
        $this->p2    = $p2;
    }

    public function query()
    {
        // Start Query
        $query = Archive::query()->with('clients');

        // 1. Filter Tanggal (Jika diisi)
        if ($this->start && $this->end) {
            try {
                $startDate = Carbon::createFromFormat('Y-m', $this->start)->startOfMonth();
                $endDate   = Carbon::createFromFormat('Y-m', $this->end)->endOfMonth();
                $query->whereBetween('tanggal_akta', [$startDate, $endDate]);
            } catch (\Exception $e) {
                // Ignore date filter if format error
            }
        }

        // 2. Filter Nama Pihak 1 (Jika diisi)
        if ($this->p1) {
            $query->whereHas('clients', function($q) {
                $q->where('peran_dalam_akta', 'Pihak Pertama')
                  ->where('nama', 'like', '%' . $this->p1 . '%');
            });
        }

        // 3. Filter Nama Pihak 2 (Jika diisi)
        if ($this->p2) {
            $query->whereHas('clients', function($q) {
                $q->where('peran_dalam_akta', 'Pihak Kedua')
                  ->where('nama', 'like', '%' . $this->p2 . '%');
            });
        }

        return $query->orderBy('tanggal_akta', 'desc');
    }

    // Mengatur data apa saja yang masuk ke kolom Excel
    public function map($archive): array
    {
        // Ambil data Pihak 1 & 2
        $p1 = $archive->clients->where('pivot.peran_dalam_akta', 'Pihak Pertama')->first();
        $p2 = $archive->clients->where('pivot.peran_dalam_akta', 'Pihak Kedua')->first();

        // Fallback untuk data lama
        if(!$p1 && $archive->clients->count() > 0) $p1 = $archive->clients->first();
        if(!$p2 && $archive->clients->count() > 1) $p2 = $archive->clients->skip(1)->first();

        return [
            $archive->nomor_order,
            $archive->nomor_akta,
            $archive->judul_akta,
            $archive->jenis_akta,
            $archive->kategori . ($archive->is_mbr ? ' (MBR)' : ''), // Info Kategori + MBR
            $p1 ? $p1->nama : '-',
            $p1 ? $p1->nik : '-',
            $p2 ? $p2->nama : '-',
            $p2 ? $p2->nik : '-',
            $archive->tanggal_akta ? Carbon::parse($archive->tanggal_akta)->format('d-m-Y') : '-',
            $archive->tanggal_akad ? Carbon::parse($archive->tanggal_akad)->format('d-m-Y') : '-',
            $archive->status == 'ARCHIVED' ? 'Selesai' : 'Proses',
            $archive->catatan_kontrol,
        ];
    }

    // Judul Kolom (Header)
    public function headings(): array
    {
        return [
            'No. Order',
            'No. Akta',
            'Judul Akta',
            'Jenis Akta',
            'Kategori',
            'Nama Pihak 1',
            'NIK Pihak 1',
            'Nama Pihak 2',
            'NIK Pihak 2',
            'Tgl Akta',
            'Tgl Akad',
            'Status',
            'Catatan',
        ];
    }

    // Bikin Header jadi Bold
    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}