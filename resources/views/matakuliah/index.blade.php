@extends('layouts.app')

@section('title', 'Data Mata Kuliah')
@section('header_title', 'Manajemen Data Mata Kuliah')
@section('header_subtitle', 'Kelola mata kuliah dan bobot SKS Universitas Suryakencana')

@section('content')
    
    @php 
        $isEdit = request()->has('edit');
        $editMk = $isEdit ? \App\Models\MataKuliah::find(request()->get('edit')) : null;
    @endphp

    <div class="card inline-form-card">
        <div class="card-title">
            {{ $isEdit ? 'Ubah Data Mata Kuliah' : 'Tambah Mata Kuliah Baru' }}
        </div>
        
        @if($isEdit && !$editMk)
            <div class="alert alert-danger" style="padding: 10px 14px; margin-bottom: 0;">
                Data mata kuliah yang ingin diubah tidak ditemukan.
            </div>
        @else
            <form action="{{ $isEdit ? route('matakuliah.update', $editMk->kode_matakuliah) : route('matakuliah.store') }}" method="POST">
                @csrf
                @if($isEdit)
                    @method('PUT')
                @endif

                <div class="form-grid" style="grid-template-columns: 1.5fr 3fr 1fr;">
                    
                    <div class="form-group">
                        <label for="kode_matakuliah">Kode Mata Kuliah</label>
                        <input type="text" name="kode_matakuliah" id="kode_matakuliah" class="form-control @error('kode_matakuliah') is-invalid @enderror" 
                               value="{{ old('kode_matakuliah', $isEdit ? $editMk->kode_matakuliah : '') }}" 
                               placeholder="Contoh: IF53413" 
                               {{ $isEdit ? 'readonly' : '' }} required>
                        @error('kode_matakuliah')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="nama_matakuliah">Nama Mata Kuliah</label>
                        <input type="text" name="nama_matakuliah" id="nama_matakuliah" class="form-control @error('nama_matakuliah') is-invalid @enderror" 
                               value="{{ old('nama_matakuliah', $isEdit ? $editMk->nama_matakuliah : '') }}" 
                               placeholder="Contoh: Pemrograman Web II" required>
                        @error('nama_matakuliah')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="sks">SKS (Bobot)</label>
                        <input type="number" name="sks" id="sks" class="form-control @error('sks') is-invalid @enderror" 
                               value="{{ old('sks', $isEdit ? $editMk->sks : '') }}" 
                               min="1" max="6" required>
                        @error('sks')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div style="display: flex; gap: 8px; margin-top: 1.25rem; justify-content: flex-end;">
                    @if($isEdit)
                        <a href="{{ route('matakuliah.index') }}" class="btn btn-secondary">Batal</a>
                    @endif
                    <button type="submit" class="btn btn-primary">
                        {{ $isEdit ? 'Simpan Perubahan' : 'Tambah Mata Kuliah' }}
                    </button>
                </div>
            </form>
        @endif
    </div>

    <div class="card">
        <div class="search-filter-bar">
            <div class="card-title" style="margin-bottom: 0;">Daftar Mata Kuliah Terdaftar</div>
            
            <form action="{{ route('matakuliah.index') }}" method="GET" class="search-input-wrapper">
                <svg class="search-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="11" cy="11" r="8"/>
                    <line x1="21" y1="21" x2="16.65" y2="16.65"/>
                </svg>
                <input type="text" name="search" class="form-control" value="{{ $search }}" placeholder="Cari berdasarkan kode atau nama mata kuliah...">
            </form>
        </div>

        @if($matakuliahs->isEmpty())
            <div style="text-align: center; padding: 3rem 0; color: var(--text-secondary);">
                <p style="font-weight: 500;">Tidak ada data mata kuliah ditemukan.</p>
                @if($search)
                    <a href="{{ route('matakuliah.index') }}" style="color: var(--primary); font-size: 13px; text-decoration: none; margin-top: 8px; display: inline-block;">Reset Pencarian</a>
                @endif
            </div>
        @else
            <div class="table-container">
                <table class="custom-table">
                    <thead>
                        <tr>
                            <th style="width: 80px;">No</th>
                            <th style="width: 180px;">Kode MK</th>
                            <th>Nama Mata Kuliah</th>
                            <th style="width: 150px;">Bobot SKS</th>
                            <th style="width: 180px; text-align: center;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($matakuliahs as $index => $mk)
                            <tr>
                                <td>{{ $matakuliahs->firstItem() + $index }}</td>
                                <td><code>{{ $mk->kode_matakuliah }}</code></td>
                                <td><strong>{{ $mk->nama_matakuliah }}</strong></td>
                                <td><span class="badge badge-primary">{{ $mk->sks }} SKS</span></td>
                                <td>
                                    <div class="actions-cell" style="justify-content: center;">
                                        
                                        <a href="{{ route('matakuliah.index', ['edit' => $mk->kode_matakuliah]) }}" class="btn btn-secondary btn-sm">
                                            Ubah
                                        </a>

                                        <form action="{{ route('matakuliah.destroy', $mk->kode_matakuliah) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus mata kuliah ini? Jadwal dan KRS mahasiswa terkait juga akan terhapus.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="pagination-container">
                {{ $matakuliahs->links() }}
            </div>
        @endif
    </div>
@endsection
