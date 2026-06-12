<?php

namespace App\Http\Controllers;

use App\Models\Krs;
use App\Models\Mahasiswa;
use App\Models\MataKuliah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KrsController extends Controller
{
    
    public function index(Request $request)
    {
        $user = Auth::user();

        if ($user->isAdmin()) {
            
            $filterMahasiswa = $request->input('mahasiswa_filter');
            $search = $request->input('search');

            $query = Krs::with(['mahasiswa.dosen', 'matakuliah']);

            if ($filterMahasiswa) {
                $query->where('npm', $filterMahasiswa);
            } elseif ($search) {
                $query->whereHas('mahasiswa', function($q) use ($search) {
                    $q->where('nama', 'like', "%{$search}%")
                      ->orWhere('npm', 'like', "%{$search}%");
                });
            }

            $krsList = $query->orderBy('npm')->paginate(20)->withQueryString();
            $mahasiswas = Mahasiswa::orderBy('nama')->get();

            return view('krs.index', compact('krsList', 'mahasiswas', 'filterMahasiswa', 'search'));
        } else {
            
            $mahasiswa = Mahasiswa::with('dosen')->where('npm', $user->npm)->firstOrFail();
            
            $krsList = Krs::with('matakuliah')
                ->where('npm', $mahasiswa->npm)
                ->get();

            $totalSks = $krsList->sum(function ($item) {
                return $item->matakuliah ? $item->matakuliah->sks : 0;
            });

            $takenCourseCodes = $krsList->pluck('kode_matakuliah')->toArray();
            $availableMatakuliahs = MataKuliah::whereNotIn('kode_matakuliah', $takenCourseCodes)
                ->orderBy('nama_matakuliah')
                ->get();

            return view('krs.index', compact('mahasiswa', 'krsList', 'totalSks', 'availableMatakuliahs'));
        }
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        if (!$user->isMahasiswa()) {
            abort(403, 'Akses dibatasi hanya untuk Mahasiswa.');
        }

        $request->validate([
            'kode_matakuliah' => 'required|string|exists:matakuliah,kode_matakuliah',
        ], [
            'kode_matakuliah.required' => 'Mata kuliah wajib dipilih.',
            'kode_matakuliah.exists' => 'Mata kuliah tidak valid.',
        ]);

        $mahasiswa = Mahasiswa::where('npm', $user->npm)->firstOrFail();
        $matakuliah = MataKuliah::findOrFail($request->kode_matakuliah);

        $alreadyTaken = Krs::where('npm', $mahasiswa->npm)
            ->where('kode_matakuliah', $request->kode_matakuliah)
            ->exists();

        if ($alreadyTaken) {
            return back()->with('error', 'Mata kuliah ini sudah diambil sebelumnya.');
        }

        $currentKrs = Krs::with('matakuliah')->where('npm', $mahasiswa->npm)->get();
        $currentSks = $currentKrs->sum(function($item) {
            return $item->matakuliah ? $item->matakuliah->sks : 0;
        });

        if ($currentSks + $matakuliah->sks > 24) {
            return back()->with('error', "Gagal mengambil mata kuliah. Batas pengambilan KRS maksimal adalah 24 SKS. SKS Anda saat ini: {$currentSks} SKS, mata kuliah yang ingin diambil: {$matakuliah->sks} SKS.");
        }

        Krs::create([
            'npm' => $mahasiswa->npm,
            'kode_matakuliah' => $request->kode_matakuliah,
        ]);

        return redirect()->route('krs.index')
            ->with('success', "Mata kuliah {$matakuliah->nama_matakuliah} ({$matakuliah->sks} SKS) berhasil ditambahkan ke KRS Anda.");
    }

    public function destroy($id)
    {
        $krs = Krs::findOrFail($id);
        $user = Auth::user();

        if ($user->isMahasiswa() && $krs->npm !== $user->npm) {
            abort(403, 'Akses ditolak.');
        }

        $krs->delete();

        return redirect()->route('krs.index')
            ->with('success', 'Mata kuliah berhasil dihapus dari KRS.');
    }

    public function print()
    {
        $user = Auth::user();
        if (!$user->isMahasiswa()) {
            abort(403);
        }

        $mahasiswa = Mahasiswa::with('dosen')->where('npm', $user->npm)->firstOrFail();
        $krsList = Krs::with('matakuliah')->where('npm', $mahasiswa->npm)->get();
        $totalSks = $krsList->sum(function ($item) {
            return $item->matakuliah ? $item->matakuliah->sks : 0;
        });

        return view('krs.print', compact('mahasiswa', 'krsList', 'totalSks'));
    }

    public function exportCsv()
    {
        $user = Auth::user();
        if (!$user->isMahasiswa()) {
            abort(403);
        }

        $mahasiswa = Mahasiswa::with('dosen')->where('npm', $user->npm)->firstOrFail();
        $krsList = Krs::with('matakuliah')->where('npm', $mahasiswa->npm)->get();

        $filename = "KRS_{$mahasiswa->npm}_{$mahasiswa->nama}.csv";

        $headers = [
            "Content-type"        => "text/csv; charset=UTF-8",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = ['No', 'Kode Mata Kuliah', 'Nama Mata Kuliah', 'SKS'];

        $callback = function() use($krsList, $columns, $mahasiswa) {
            $file = fopen('php://output', 'w');
            
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            fputcsv($file, ['KARTU RENCANA STUDI (KRS)']);
            fputcsv($file, ['NPM', $mahasiswa->npm]);
            fputcsv($file, ['Nama Mahasiswa', $mahasiswa->nama]);
            fputcsv($file, ['Dosen Wali', $mahasiswa->dosen ? $mahasiswa->dosen->nama : '-']);
            fputcsv($file, []); 

            fputcsv($file, $columns);

            $totalSks = 0;
            foreach ($krsList as $index => $krs) {
                $sks = $krs->matakuliah ? $krs->matakuliah->sks : 0;
                $totalSks += $sks;

                fputcsv($file, [
                    $index + 1,
                    $krs->kode_matakuliah,
                    $krs->matakuliah ? $krs->matakuliah->nama_matakuliah : '-',
                    $sks,
                ]);
            }

            fputcsv($file, []);
            fputcsv($file, ['', '', 'Total SKS', $totalSks]);
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
