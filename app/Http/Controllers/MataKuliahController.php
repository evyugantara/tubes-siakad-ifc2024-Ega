<?php

namespace App\Http\Controllers;

use App\Models\MataKuliah;
use Illuminate\Http\Request;

class MataKuliahController extends Controller
{
    
    public function index(Request $request)
    {
        $search = $request->input('search');

        $query = MataKuliah::query();

        if ($search) {
            $query->where('kode_matakuliah', 'like', "%{$search}%")
                  ->orWhere('nama_matakuliah', 'like', "%{$search}%");
        }

        $matakuliahs = $query->orderBy('kode_matakuliah')->paginate(10)->withQueryString();

        return view('matakuliah.index', compact('matakuliahs', 'search'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_matakuliah' => 'required|string|size:8|unique:matakuliah,kode_matakuliah',
            'nama_matakuliah' => 'required|string|max:50',
            'sks' => 'required|integer|min:1|max:6',
        ], [
            'kode_matakuliah.required' => 'Kode Mata Kuliah wajib diisi.',
            'kode_matakuliah.size' => 'Kode Mata Kuliah harus berukuran 8 karakter.',
            'kode_matakuliah.unique' => 'Kode Mata Kuliah sudah terdaftar.',
            'nama_matakuliah.required' => 'Nama Mata Kuliah wajib diisi.',
            'nama_matakuliah.max' => 'Nama Mata Kuliah maksimal 50 karakter.',
            'sks.required' => 'SKS wajib diisi.',
            'sks.integer' => 'SKS harus berupa angka.',
            'sks.min' => 'SKS minimal 1.',
            'sks.max' => 'SKS maksimal 6.',
        ]);

        MataKuliah::create($request->only('kode_matakuliah', 'nama_matakuliah', 'sks'));

        return redirect()->route('matakuliah.index')
            ->with('success', 'Mata kuliah berhasil ditambahkan.');
    }

    public function update(Request $request, $kode)
    {
        $matakuliah = MataKuliah::findOrFail($kode);

        $request->validate([
            'nama_matakuliah' => 'required|string|max:50',
            'sks' => 'required|integer|min:1|max:6',
        ], [
            'nama_matakuliah.required' => 'Nama Mata Kuliah wajib diisi.',
            'nama_matakuliah.max' => 'Nama Mata Kuliah maksimal 50 karakter.',
            'sks.required' => 'SKS wajib diisi.',
            'sks.integer' => 'SKS harus berupa angka.',
            'sks.min' => 'SKS minimal 1.',
            'sks.max' => 'SKS maksimal 6.',
        ]);

        $matakuliah->update($request->only('nama_matakuliah', 'sks'));

        return redirect()->route('matakuliah.index')
            ->with('success', 'Mata kuliah berhasil diperbarui.');
    }

    public function destroy($kode)
    {
        $matakuliah = MataKuliah::findOrFail($kode);
        $matakuliah->delete();

        return redirect()->route('matakuliah.index')
            ->with('success', 'Mata kuliah berhasil dihapus.');
    }
}
