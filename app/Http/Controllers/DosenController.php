<?php

namespace App\Http\Controllers;

use App\Models\Dosen;
use Illuminate\Http\Request;

class DosenController extends Controller
{
    
    public function index(Request $request)
    {
        $search = $request->input('search');

        $query = Dosen::query();

        if ($search) {
            $query->where('nidn', 'like', "%{$search}%")
                  ->orWhere('nama', 'like', "%{$search}%");
        }

        $dosens = $query->orderBy('nidn')->paginate(10)->withQueryString();

        return view('dosen.index', compact('dosens', 'search'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nidn' => 'required|string|size:10|unique:dosen,nidn',
            'nama' => 'required|string|max:50',
        ], [
            'nidn.required' => 'NIDN wajib diisi.',
            'nidn.string' => 'NIDN harus berupa teks.',
            'nidn.size' => 'NIDN harus berukuran 10 karakter.',
            'nidn.unique' => 'NIDN sudah terdaftar di sistem.',
            'nama.required' => 'Nama dosen wajib diisi.',
            'nama.string' => 'Nama dosen harus berupa teks.',
            'nama.max' => 'Nama dosen maksimal 50 karakter.',
        ]);

        Dosen::create($request->only('nidn', 'nama'));

        return redirect()->route('dosen.index')
            ->with('success', 'Data dosen berhasil ditambahkan.');
    }

    public function update(Request $request, $nidn)
    {
        $dosen = Dosen::findOrFail($nidn);

        $request->validate([
            'nama' => 'required|string|max:50',
        ], [
            'nama.required' => 'Nama dosen wajib diisi.',
            'nama.string' => 'Nama dosen harus berupa teks.',
            'nama.max' => 'Nama dosen maksimal 50 karakter.',
        ]);

        $dosen->update($request->only('nama'));

        return redirect()->route('dosen.index')
            ->with('success', 'Data dosen berhasil diperbarui.');
    }

    public function destroy($nidn)
    {
        $dosen = Dosen::findOrFail($nidn);
        $dosen->delete();

        return redirect()->route('dosen.index')
            ->with('success', 'Data dosen berhasil dihapus.');
    }
}
