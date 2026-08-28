<?php

namespace App\Http\Controllers;

use App\Models\Archive;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ArchivesExport;

class ArchiveController extends Controller
{
    // 1. DASHBOARD
    public function index(Request $request)
    {
        $query = Archive::with('clients')->where('status', 'PROCESS');

        // 🔥 FITUR BARU: Filter Data Belum Lengkap
        if ($request->has('filter') && $request->filter == 'incomplete') {
            $query->where(function($q) {
                $q->whereNull('nomor_akta')
                  ->orWhereNull('tanggal_akta')
                  ->orWhereNull('file_path');
            });
        }

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nomor_akta', 'like', "%{$search}%")
                  ->orWhere('judul_akta', 'like', "%{$search}%")
                  ->orWhere('assigned_to', 'like', "%{$search}%")
                  ->orWhereHas('clients', function($c) use ($search) {
                      $c->where('nama', 'like', "%{$search}%");
                  });
            });
        }
        $ongoing = $query->orderBy('deadline', 'asc')->get();
        return view('archives.index', compact('ongoing'));
    }

    // 2. ARSIP LIST
    public function list(Request $request)
    {
        $monthList = Archive::where('status', 'ARCHIVED')
            ->selectRaw('YEAR(tanggal_akta) as year, MONTH(tanggal_akta) as month, COUNT(*) as total')
            ->groupBy('year', 'month')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->get();

        $query = Archive::with('clients')->where('status', 'ARCHIVED');

        if ($request->has('month') && $request->has('year')) {
            $query->whereYear('tanggal_akta', $request->year)
                  ->whereMonth('tanggal_akta', $request->month);
        }

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nomor_akta', 'like', "%{$search}%")
                  ->orWhere('judul_akta', 'like', "%{$search}%")
                  ->orWhereHas('clients', function($c) use ($search) {
                      $c->where('nama', 'like', "%{$search}%");
                  });
            });
        }
        $archives = $query->latest('tanggal_akta')->get();
        return view('archives.list', compact('archives', 'monthList'));
    }

    // 3. CREATE FORM
    public function create()
    {
        return view('archives.create');
    }

    // 4. STORE DATA (100% Bebas Kosong)
    public function store(Request $request)
    {
        // Semua diubah jadi nullable
        $request->validate([
            'judul_akta'  => 'nullable',
            'nomor_akta'  => 'nullable',
            'deadline'    => 'nullable|date',
            'assigned_to' => 'nullable',
            'nama_pihak1' => 'nullable',
        ]);

        $filePath = null;
        if ($request->hasFile('file_dokumen')) {
            $filePath = $request->file('file_dokumen')->store('dokumen_akta', 'public');
        }

        // Trik Penamaan Otomatis jika Judul kosong
        $judul = $request->filled('judul_akta') ? $request->judul_akta : 'Draf (Belum Ada Judul)';

        // 1. Simpan Data Arsip
        $archive = Archive::create([
            'nomor_order' => $request->nomor_order,
            'nomor_akta' => $request->nomor_akta,
            'tanggal_akta' => $request->tanggal_akta,
            'tanggal_akad' => $request->tanggal_akad,
            'judul_akta' => $judul, // Masukin variabel $judul ke sini
            'jenis_akta' => $request->jenis_akta,
            'kategori' => $request->kategori,
            'is_mbr' => $request->has('is_mbr') ? 1 : 0, 
            'tahapan' => 'Pemberkasan',
            'keterangan' => $request->keterangan,
            'file_path' => $filePath,
            'deadline' => $request->deadline,
            'assigned_to' => $request->assigned_to,
            'status' => 'PROCESS',
            'checklist_items' => $request->checklist,
            'catatan_kontrol' => $request->catatan_kontrol,
        ]);

        $clientData = [];

        // 2. Simpan PIHAK PERTAMA (Dicek dulu ada isinya nggak)
        if($request->filled('nama_pihak1')) {
            $client1 = Client::create([
                'nama' => $request->nama_pihak1,
                'nik'  => $request->nik_pihak1,
            ]);
            $clientData[$client1->id] = ['peran_dalam_akta' => 'Pihak Pertama'];
        }

        // 3. Simpan PIHAK KEDUA (Jika ada)
        if($request->filled('nama_pihak2')) {
            $client2 = Client::create([
                'nama' => $request->nama_pihak2,
                'nik'  => $request->nik_pihak2,
            ]);
            $clientData[$client2->id] = ['peran_dalam_akta' => 'Pihak Kedua'];
        }
        
        // Sync data client
        if (!empty($clientData)) {
            $archive->clients()->sync($clientData);
        }

        return redirect()->route('archives.index')->with('success', 'Draf pekerjaan baru berhasil dibuat!');
    }

    // 5. SHOW DETAIL
    public function show($uuid)
    {
        $archive = Archive::where('uuid', $uuid)->with('clients')->firstOrFail();
        return view('archives.show', compact('archive'));
    }

    // 6. EDIT FORM
    public function edit($uuid)
    {
        $archive = Archive::where('uuid', $uuid)->with('clients')->firstOrFail();
        return view('archives.edit', compact('archive'));
    }

    // 7. UPDATE DATA (100% Bebas Kosong)
    public function update(Request $request, $uuid)
    {
        $archive = Archive::where('uuid', $uuid)->firstOrFail();

        // Semua diubah jadi nullable
        $request->validate([
            'judul_akta'  => 'nullable',
            'nomor_akta'  => 'nullable',
            'deadline'    => 'nullable|date',
            'nama_pihak1' => 'nullable',
        ]);

        // 1. Handle File Upload
        if ($request->hasFile('file_dokumen')) {
            if ($archive->file_path && Storage::disk('public')->exists($archive->file_path)) {
                Storage::disk('public')->delete($archive->file_path);
            }
            $archive->file_path = $request->file('file_dokumen')->store('dokumen_akta', 'public');
        }

        // Trik Penamaan Otomatis untuk Edit
        $judul = $request->filled('judul_akta') ? $request->judul_akta : 'Draf (Belum Ada Judul)';

        // 2. Update Data Arsip
        $archive->update([
            'nomor_order'  => $request->nomor_order,
            'nomor_akta'   => $request->nomor_akta,
            'tanggal_akta' => $request->tanggal_akta,
            'tanggal_akad' => $request->tanggal_akad,
            'judul_akta'   => $judul, // Masukin variabel $judul ke sini
            'jenis_akta'   => $request->jenis_akta,
            'kategori'     => $request->kategori,
            'is_mbr'       => $request->has('is_mbr') ? 1 : 0,
            'keterangan'   => $request->keterangan,
            'deadline'     => $request->deadline,
            'assigned_to'  => $request->assigned_to,
            'checklist_items' => $request->checklist,
            'catatan_kontrol' => $request->catatan_kontrol,
        ]);

        $clientDataToSync = [];

        // 3. Update / Insert PIHAK PERTAMA
        if ($request->filled('nama_pihak1')) {
            $client1 = $archive->clients()->wherePivot('peran_dalam_akta', 'Pihak Pertama')->first();
            
            if ($client1) {
                $client1->update([
                    'nama' => $request->nama_pihak1,
                    'nik'  => $request->nik_pihak1,
                ]);
                $clientDataToSync[$client1->id] = ['peran_dalam_akta' => 'Pihak Pertama'];
            } else {
                $newClient1 = Client::create([
                    'nama' => $request->nama_pihak1,
                    'nik'  => $request->nik_pihak1,
                ]);
                $clientDataToSync[$newClient1->id] = ['peran_dalam_akta' => 'Pihak Pertama'];
            }
        }

        // 4. Update / Insert PIHAK KEDUA
        if ($request->filled('nama_pihak2')) {
            $client2 = $archive->clients()->wherePivot('peran_dalam_akta', 'Pihak Kedua')->first();

            if ($client2) {
                $client2->update([
                    'nama' => $request->nama_pihak2,
                    'nik'  => $request->nik_pihak2,
                ]);
                $clientDataToSync[$client2->id] = ['peran_dalam_akta' => 'Pihak Kedua'];
            } else {
                $newClient2 = Client::create([
                    'nama' => $request->nama_pihak2,
                    'nik'  => $request->nik_pihak2,
                ]);
                $clientDataToSync[$newClient2->id] = ['peran_dalam_akta' => 'Pihak Kedua'];
            }
        }

        // 5. Sync Relasi
        $archive->clients()->sync($clientDataToSync);

        $route = $archive->status == 'PROCESS' ? 'archives.index' : 'archives.list';
        return redirect()->route($route)->with('success', 'Data berhasil diperbaharui!');
    }

    // 8. DELETE DATA
    public function destroy($id)
    {
        $archive = Archive::findOrFail($id);
        if ($archive->file_path && Storage::disk('public')->exists($archive->file_path)) {
            Storage::disk('public')->delete($archive->file_path);
        }
        $archive->delete();
        return redirect()->back()->with('success', 'Data berhasil dihapus permanen.');
    }

    // 9. MARK AS COMPLETE
    public function markAsComplete($id)
    {
        $archive = Archive::findOrFail($id);
        $archive->update([
            'status' => 'ARCHIVED',
            'tahapan' => 'Selesai'
        ]);
        return redirect()->back()->with('success', 'Pekerjaan selesai! Data dipindahkan ke Arsip Digital.');
    }

    // 10. UPDATE TAHAPAN
    public function updateTahapan(Request $request, $id)
    {
        $archive = Archive::findOrFail($id);
        $archive->update(['tahapan' => $request->tahapan]);
        return redirect()->back()->with('success', 'Status tahapan berhasil diperbarui!');
    }

    // 11. EXPORT EXCEL
    public function exportExcel(Request $request)
    {
        $start = $request->start_month;
        $end   = $request->end_month;
        $p1    = $request->nama_pihak1;
        $p2    = $request->nama_pihak2;
        
        $fileName = 'Laporan_Notaris';
        if($start && $end) {
            $fileName .= '_Periode_' . $start . '_sd_' . $end;
        } else {
            $fileName .= '_Semua_Data';
        }

        if($p1 || $p2) {
            $fileName .= '_Filtered';
        }

        $fileName .= '.xlsx';

        return Excel::download(new ArchivesExport($start, $end, $p1, $p2), $fileName);
    }

    // 12. TRACKING KLIEN
    public function clientTracking($uuid)
    {
        $archive = Archive::where('uuid', $uuid)->with('clients')->firstOrFail();
        return view('tracking.show', compact('archive'));
    }

    // 13. RESTORE
    public function restore($id)
    {
        $archive = Archive::findOrFail($id);
        $archive->update([
            'status' => 'PROCESS'
        ]);
        return redirect()->route('archives.index')->with('success', 'Data berhasil dikembalikan ke Dashboard Aktif!');
    }
}