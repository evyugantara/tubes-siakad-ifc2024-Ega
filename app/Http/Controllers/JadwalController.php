<?php

namespace App\Http\Controllers;

use App\Models\Jadwal;
use App\Models\Dosen;
use App\Models\MataKuliah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class JadwalController extends Controller
{
    
    public function index(Request $request)
    {
        $search = $request->input('search');
        $filterHari = $request->input('hari_filter');

        $query = Jadwal::with(['matakuliah', 'dosen']);

        if ($search) {
            $query->whereHas('matakuliah', function($q) use ($search) {
                $q->where('nama_matakuliah', 'like', "%{$search}%")
                  ->orWhere('kode_matakuliah', 'like', "%{$search}%");
            })->orWhereHas('dosen', function($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%");
            });
        }

        if ($filterHari) {
            $query->where('hari', $filterHari);
        }

        $jadwals = $query->orderByRaw("FIELD(hari, 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu')")
            ->orderBy('jam')
            ->paginate(15)
            ->withQueryString();

        $dosens = Dosen::orderBy('nama')->get();
        $matakuliahs = MataKuliah::orderBy('nama_matakuliah')->get();

        return view('jadwal.index', compact('jadwals', 'dosens', 'matakuliahs', 'search', 'filterHari'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_matakuliah' => 'required|string|exists:pwl_kelas_b_matakuliah,kode_matakuliah',
            'nidn' => 'required|string|exists:pwl_kelas_b_dosen,nidn',
            'kelas' => 'required|string|size:1',
            'hari' => 'required|string|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu,Minggu',
            'jam' => 'required', 
        ], [
            'kode_matakuliah.required' => 'Mata Kuliah wajib dipilih.',
            'kode_matakuliah.exists' => 'Mata Kuliah tidak valid.',
            'nidn.required' => 'Dosen Wali/Pengajar wajib dipilih.',
            'nidn.exists' => 'Dosen Pengajar tidak valid.',
            'kelas.required' => 'Kelas wajib diisi.',
            'kelas.size' => 'Kelas harus berukuran 1 karakter (misal: A, B, C).',
            'hari.required' => 'Hari wajib diisi.',
            'hari.in' => 'Hari harus berupa hari kerja/kuliah yang valid.',
            'jam.required' => 'Jam kuliah wajib diisi.',
        ]);

        $jamTimestamp = Carbon::createFromFormat('H:i', $request->jam)->setDate(2026, 1, 1)->toDateTimeString();

        Jadwal::create([
            'kode_matakuliah' => $request->kode_matakuliah,
            'nidn' => $request->nidn,
            'kelas' => strtoupper($request->kelas),
            'hari' => $request->hari,
            'jam' => $jamTimestamp,
        ]);

        return redirect()->route('jadwal.index')
            ->with('success', 'Jadwal kuliah baru berhasil dibuat.');
    }

    public function update(Request $request, $id)
    {
        $jadwal = Jadwal::findOrFail($id);

        $request->validate([
            'kode_matakuliah' => 'required|string|exists:pwl_kelas_b_matakuliah,kode_matakuliah',
            'nidn' => 'required|string|exists:pwl_kelas_b_dosen,nidn',
            'kelas' => 'required|string|size:1',
            'hari' => 'required|string|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu,Minggu',
            'jam' => 'required',
        ], [
            'kode_matakuliah.required' => 'Mata Kuliah wajib dipilih.',
            'kode_matakuliah.exists' => 'Mata Kuliah tidak valid.',
            'nidn.required' => 'Dosen Wali/Pengajar wajib dipilih.',
            'nidn.exists' => 'Dosen Pengajar tidak valid.',
            'kelas.required' => 'Kelas wajib diisi.',
            'kelas.size' => 'Kelas harus berukuran 1 karakter (misal: A, B, C).',
            'hari.required' => 'Hari wajib diisi.',
            'hari.in' => 'Hari harus berupa hari kerja/kuliah yang valid.',
            'jam.required' => 'Jam kuliah wajib diisi.',
        ]);

        $jamTimestamp = Carbon::createFromFormat('H:i', $request->jam)->setDate(2026, 1, 1)->toDateTimeString();

        $jadwal->update([
            'kode_matakuliah' => $request->kode_matakuliah,
            'nidn' => $request->nidn,
            'kelas' => strtoupper($request->kelas),
            'hari' => $request->hari,
            'jam' => $jamTimestamp,
        ]);

        return redirect()->route('jadwal.index')
            ->with('success', 'Jadwal kuliah berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $jadwal = Jadwal::findOrFail($id);
        $jadwal->delete();

        return redirect()->route('jadwal.index')
            ->with('success', 'Jadwal kuliah berhasil dihapus.');
    }
}
