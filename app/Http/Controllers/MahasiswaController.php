<?php

namespace App\Http\Controllers;

use App\Models\Mahasiswa;
use App\Models\Dosen;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class MahasiswaController extends Controller
{
    
    public function index(Request $request)
    {
        $search = $request->input('search');
        $filterDosen = $request->input('dosen_filter');

        $query = Mahasiswa::with('dosen');

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('npm', 'like', "%{$search}%")
                  ->orWhere('nama', 'like', "%{$search}%");
            });
        }

        if ($filterDosen) {
            $query->where('nidn', $filterDosen);
        }

        $mahasiswas = $query->orderBy('npm')->paginate(10)->withQueryString();
        $dosens = Dosen::orderBy('nama')->get();

        return view('mahasiswa.index', compact('mahasiswas', 'dosens', 'search', 'filterDosen'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'npm' => 'required|string|size:10|unique:mahasiswa,npm',
            'nama' => 'required|string|max:50',
            'nidn' => 'required|string|exists:dosen,nidn',
        ], [
            'npm.required' => 'NPM wajib diisi.',
            'npm.size' => 'NPM harus berukuran 10 karakter.',
            'npm.unique' => 'NPM sudah terdaftar di sistem.',
            'nama.required' => 'Nama mahasiswa wajib diisi.',
            'nama.max' => 'Nama mahasiswa maksimal 50 karakter.',
            'nidn.required' => 'Dosen Wali wajib dipilih.',
            'nidn.exists' => 'Dosen Wali tidak valid.',
        ]);

        DB::transaction(function () use ($request) {
            
            Mahasiswa::create([
                'npm' => $request->npm,
                'nama' => $request->nama,
                'nidn' => $request->nidn,
            ]);

            User::create([
                'name' => $request->nama,
                'email' => $request->npm . '@student.unsur.ac.id',
                'username' => $request->npm,
                'password' => Hash::make($request->npm), 
                'role' => 'mahasiswa',
                'npm' => $request->npm,
            ]);
        });

        return redirect()->route('mahasiswa.index')
            ->with('success', "Data mahasiswa {$request->nama} berhasil ditambahkan. Akun login otomatis dibuat (Username: {$request->npm}, Password: {$request->npm}).");
    }

    public function update(Request $request, $npm)
    {
        $mahasiswa = Mahasiswa::findOrFail($npm);

        $request->validate([
            'nama' => 'required|string|max:50',
            'nidn' => 'required|string|exists:dosen,nidn',
        ], [
            'nama.required' => 'Nama mahasiswa wajib diisi.',
            'nama.max' => 'Nama mahasiswa maksimal 50 karakter.',
            'nidn.required' => 'Dosen Wali wajib dipilih.',
            'nidn.exists' => 'Dosen Wali tidak valid.',
        ]);

        DB::transaction(function () use ($request, $mahasiswa) {
            
            $mahasiswa->update([
                'nama' => $request->nama,
                'nidn' => $request->nidn,
            ]);

            User::where('npm', $mahasiswa->npm)->update([
                'name' => $request->nama,
            ]);
        });

        return redirect()->route('mahasiswa.index')
            ->with('success', 'Data mahasiswa berhasil diperbarui.');
    }

    public function destroy($npm)
    {
        $mahasiswa = Mahasiswa::findOrFail($npm);
        
        $mahasiswa->delete();

        return redirect()->route('mahasiswa.index')
            ->with('success', 'Data mahasiswa dan akun loginnya berhasil dihapus.');
    }
}
