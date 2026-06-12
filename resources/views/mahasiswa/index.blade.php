@extends('layouts.app')

@section('title', 'Data Mahasiswa')
@section('header_title', 'Manajemen Data Mahasiswa')
@section('header_subtitle', 'Kelola data mahasiswa dan akun login mereka')

@section('content')
    
    @php 
        $isEdit = request()->has('edit');
        $editMhs = $isEdit ? \App\Models\Mahasiswa::find(request()->get('edit')) : null;
    @endphp

    <div class="card inline-form-card">
        <div class="card-title">
            {{ $isEdit ? 'Ubah Data Mahasiswa' : 'Tambah Mahasiswa Baru' }}
        </div>
        
        @if($isEdit && !$editMhs)
            <div class="alert alert-danger" style="padding: 10px 14px; margin-bottom: 0;">
                Data mahasiswa yang ingin diubah tidak ditemukan.
            </div>
        @else
            <form action="{{ $isEdit ? route('mahasiswa.update', $editMhs->npm) : route('mahasiswa.store') }}" method="POST">
                @csrf
                @if($isEdit)
                    @method('PUT')
                @endif

                <div class="form-grid" style="grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));">
                    
                    <div class="form-group">
                        <label for="npm">NPM (Nomor Pokok Mahasiswa)</label>
                        <input type="text" name="npm" id="npm" class="form-control @error('npm') is-invalid @enderror" 
                               value="{{ old('npm', $isEdit ? $editMhs->npm : '') }}" 
                               placeholder="10 digit angka" 
                               {{ $isEdit ? 'readonly' : '' }} required>
                        @error('npm')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="nama">Nama Lengkap Mahasiswa</label>
                        <input type="text" name="nama" id="nama" class="form-control @error('nama') is-invalid @enderror" 
                               value="{{ old('nama', $isEdit ? $editMhs->nama : '') }}" 
                               placeholder="Nama lengkap" required>
                        @error('nama')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="nidn">Dosen Wali Akademik</label>
                        <select name="nidn" id="nidn" class="form-control @error('nidn') is-invalid @enderror" required>
                            <option value="">-- Pilih Dosen Wali --</option>
                            @foreach($dosens as $dosen)
                                <option value="{{ $dosen->nidn }}" {{ old('nidn', $isEdit ? $editMhs->nidn : '') === $dosen->nidn ? 'selected' : '' }}>
                                    {{ $dosen->nama }} ({{ $dosen->nidn }})
                                </option>
                            @endforeach
                        </select>
                        @error('nidn')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                @if(!$isEdit)
                    <div style="font-size: 13px; color: var(--text-secondary); margin-top: 1rem; display: flex; align-items: center; gap: 6px;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="var(--primary)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"/>
                            <line x1="12" y1="16" x2="12" y2="12"/>
                            <line x1="12" y1="8" x2="12.01" y2="8"/>
                        </svg>
                        <span>Akun login untuk mahasiswa akan dibuat secara otomatis dengan <strong>Username = NPM</strong> dan <strong>Password = NPM</strong>.</span>
                    </div>
                @endif

                <div style="display: flex; gap: 8px; margin-top: 1.25rem; justify-content: flex-end;">
                    @if($isEdit)
                        <a href="{{ route('mahasiswa.index') }}" class="btn btn-secondary">Batal</a>
                    @endif
                    <button type="submit" class="btn btn-primary">
                        {{ $isEdit ? 'Simpan Perubahan' : 'Tambah Mahasiswa' }}
                    </button>
                </div>
            </form>
        @endif
    </div>

    <div class="card">
        <div class="search-filter-bar">
            <div class="card-title" style="margin-bottom: 0;">Daftar Mahasiswa Terdaftar</div>
            
            <div style="display: flex; gap: 12px; flex-grow: 1; justify-content: flex-end; max-width: 700px; width: 100%; flex-wrap: wrap;">
                
                <form action="{{ route('mahasiswa.index') }}" method="GET" style="display: flex; gap: 8px;">
                    @if($search)
                        <input type="hidden" name="search" value="{{ $search }}">
                    @endif
                    <select name="dosen_filter" class="form-control" onchange="this.form.submit()" style="padding: 8px 12px; height: 40px; min-width: 200px;">
                        <option value="">-- Semua Dosen Wali --</option>
                        @foreach($dosens as $dosen)
                            <option value="{{ $dosen->nidn }}" {{ $filterDosen === $dosen->nidn ? 'selected' : '' }}>
                                {{ $dosen->nama }}
                            </option>
                        @endforeach
                    </select>
                </form>

                <form action="{{ route('mahasiswa.index') }}" method="GET" class="search-input-wrapper" style="max-width: 300px;">
                    @if($filterDosen)
                        <input type="hidden" name="dosen_filter" value="{{ $filterDosen }}">
                    @endif
                    <svg class="search-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="11" cy="11" r="8"/>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"/>
                    </svg>
                    <input type="text" name="search" class="form-control" value="{{ $search }}" placeholder="Cari berdasarkan NPM atau nama..." style="height: 40px;">
                </form>
            </div>
        </div>

        @if($mahasiswas->isEmpty())
            <div style="text-align: center; padding: 3rem 0; color: var(--text-secondary);">
                <p style="font-weight: 500;">Tidak ada data mahasiswa ditemukan.</p>
                @if($search || $filterDosen)
                    <a href="{{ route('mahasiswa.index') }}" style="color: var(--primary); font-size: 13px; text-decoration: none; margin-top: 8px; display: inline-block;">Reset Filter & Pencarian</a>
                @endif
            </div>
        @else
            <div class="table-container">
                <table class="custom-table">
                    <thead>
                        <tr>
                            <th style="width: 80px;">No</th>
                            <th style="width: 180px;">NPM</th>
                            <th>Nama Mahasiswa</th>
                            <th>Dosen Wali Akademik</th>
                            <th style="width: 180px; text-align: center;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($mahasiswas as $index => $mhs)
                            <tr>
                                <td>{{ $mahasiswas->firstItem() + $index }}</td>
                                <td><code>{{ $mhs->npm }}</code></td>
                                <td><strong>{{ $mhs->nama }}</strong></td>
                                <td>
                                    @if($mhs->dosen)
                                        {{ $mhs->dosen->nama }}
                                        <div style="font-size: 11px; color: var(--text-secondary); margin-top: 2px;">NIDN: {{ $mhs->nidn }}</div>
                                    @else
                                        <span class="badge badge-danger">Belum Ditentukan</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="actions-cell" style="justify-content: center;">
                                        
                                        <a href="{{ route('mahasiswa.index', ['edit' => $mhs->npm]) }}" class="btn btn-secondary btn-sm">
                                            Ubah
                                        </a>

                                        <form action="{{ route('mahasiswa.destroy', $mhs->npm) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data mahasiswa ini? Akun login dan KRS terkait juga akan dihapus.');">
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
                {{ $mahasiswas->links() }}
            </div>
        @endif
    </div>
@endsection
