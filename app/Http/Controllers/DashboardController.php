<?php

namespace App\Http\Controllers;

use App\Models\Dosen;
use App\Models\Mahasiswa;
use App\Models\MataKuliah;
use App\Models\Jadwal;
use App\Models\Krs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    
    public function index()
    {
        $user = Auth::user();

        if ($user->isAdmin()) {
            
            $stats = [
                'total_dosen' => Dosen::count(),
                'total_mahasiswa' => Mahasiswa::count(),
                'total_matakuliah' => MataKuliah::count(),
                'total_jadwal' => Jadwal::count(),
            ];

            return view('dashboard', compact('stats'));
        } else {
            
            $mahasiswa = Mahasiswa::with('dosen')->where('npm', $user->npm)->first();
            
            if (!$mahasiswa) {
                abort(404, 'Profil mahasiswa tidak ditemukan.');
            }

            $krsActive = Krs::with('matakuliah')
                ->where('npm', $mahasiswa->npm)
                ->get();

            $totalSks = $krsActive->sum(function ($item) {
                return $item->matakuliah ? $item->matakuliah->sks : 0;
            });

            $totalMatakuliah = $krsActive->count();

            $enrolledCourseCodes = $krsActive->pluck('kode_matakuliah')->toArray();
            $personalJadwal = Jadwal::with(['matakuliah', 'dosen'])
                ->whereIn('kode_matakuliah', $enrolledCourseCodes)
                ->orderByRaw("FIELD(hari, 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu')")
                ->orderBy('jam')
                ->get();

            return view('dashboard', compact('mahasiswa', 'totalSks', 'totalMatakuliah', 'personalJadwal'));
        }
    }
}
