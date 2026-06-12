@extends('layouts.app')

@section('title', 'Data Dosen')
@section('header_title', 'Manajemen Data Dosen')
@section('header_subtitle', 'Kelola data dosen pengajar Universitas Suryakencana')

@section('content')
    
    @php 
        $isEdit = request()->has('edit');
        $editDosen = $isEdit ? \App\Models\Dosen::find(request()->get('edit')) : null;
    @endphp

    <div class="card inline-form-card">
        <div class="card-title">
            {{ $isEdit ? 'Ubah Data Dosen' : 'Tambah Dosen Baru' }}
        </div>
        
        @if($isEdit && !$editDosen)
            <div class="alert alert-danger" style="padding: 10px 14px; margin-bottom: 0;">
                Data dosen yang ingin diubah tidak ditemukan.
            </div>
        @else
            <form action="{{ $isEdit ? route('dosen.update', $editDosen->nidn) : route('dosen.store') }}" method="POST">
                @csrf
                @if($isEdit)
                    @method('PUT')
                @endif

                <div class="form-grid">
                    
                    <div class="form-group">
                        <label for="nidn">NIDN (Nomor Induk Dosen Nasional)</label>
                        <input type="text" name="nidn" id="nidn" class="form-control @error('nidn') is-invalid @enderror" 
                               value="{{ old('nidn', $isEdit ? $editDosen->nidn : '') }}" 
                               placeholder="10 digit angka" 
                               {{ $isEdit ? 'readonly' : '' }} required>
                        @error('nidn')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="nama">Nama Lengkap Dosen</label>
                        <input type="text" name="nama" id="nama" class="form-control @error('nama') is-invalid @enderror" 
                               value="{{ old('nama', $isEdit ? $editDosen->nama : '') }}" 
                               placeholder="Nama beserta gelar" required>
                        @error('nama')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div style="display: flex; gap: 8px; margin-top: 1.25rem; justify-content: flex-end;">
                    @if($isEdit)
                        <a href="{{ route('dosen.index') }}" class="btn btn-secondary">Batal</a>
                    @endif
                    <button type="submit" class="btn btn-primary">
                        {{ $isEdit ? 'Simpan Perubahan' : 'Tambah Dosen' }}
                    </button>
                </div>
            </form>
        @endif
    </div>

    <div class="card">
        <div class="search-filter-bar">
            <div class="card-title" style="margin-bottom: 0;">Daftar Dosen Pengajar</div>
            
            <form action="{{ route('dosen.index') }}" method="GET" class="search-input-wrapper">
                <svg class="search-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="11" cy="11" r="8"/>
                    <line x1="21" y1="21" x2="16.65" y2="16.65"/>
                </svg>
                <input type="text" name="search" class="form-control" value="{{ $search }}" placeholder="Cari berdasarkan NIDN atau nama...">
            </form>
        </div>

        @if($dosens->isEmpty())
            <div style="text-align: center; padding: 3rem 0; color: var(--text-secondary);">
                <p style="font-weight: 500;">Tidak ada data dosen ditemukan.</p>
                @if($search)
                    <a href="{{ route('dosen.index') }}" style="color: var(--primary); font-size: 13px; text-decoration: none; margin-top: 8px; display: inline-block;">Reset Pencarian</a>
                @endif
            </div>
        @else
            <div class="table-container">
                <table class="custom-table">
                    <thead>
                        <tr>
                            <th style="width: 80px;">No</th>
                            <th style="width: 200px;">NIDN</th>
                            <th>Nama Lengkap Dosen</th>
                            <th style="width: 200px; text-align: center;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($dosens as $index => $dosen)
                            <tr>
                                <td>{{ $dosens->firstItem() + $index }}</td>
                                <td><code>{{ $dosen->nidn }}</code></td>
                                <td><strong>{{ $dosen->nama }}</strong></td>
                                <td>
                                    <div class="actions-cell" style="justify-content: center;">
                                        
                                        <a href="{{ route('dosen.index', ['edit' => $dosen->nidn]) }}" class="btn btn-secondary btn-sm" title="Ubah Data">
                                            Ubah
                                        </a>

                                        <form action="{{ route('dosen.destroy', $dosen->nidn) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data dosen ini? Tindakan ini akan menghapus jadwal terkait.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" title="Hapus Data">
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
                {{ $dosens->links() }}
            </div>
        @endif
    </div>
@endsection
