@extends('layouts.app')

@section('title', 'Jadwal Kuliah')
@section('header_title', 'Jadwal Perkuliahan')
@section('header_subtitle', 'Informasi jadwal perkuliahan Universitas Suryakencana')

@section('content')
    
    @if(auth()->user()->isAdmin())
        @php 
            $isEdit = request()->has('edit');
            $editJadwal = $isEdit ? \App\Models\Jadwal::find(request()->get('edit')) : null;
        @endphp

        <div class="card inline-form-card">
            <div class="card-title">
                {{ $isEdit ? 'Ubah Jadwal Kuliah' : 'Tambah Jadwal Kuliah Baru' }}
            </div>
            
            @if($isEdit && !$editJadwal)
                <div class="alert alert-danger" style="padding: 10px 14px; margin-bottom: 0;">
                    Data jadwal yang ingin diubah tidak ditemukan.
                </div>
            @else
                <form action="{{ $isEdit ? route('jadwal.update', $editJadwal->id) : route('jadwal.store') }}" method="POST">
                    @csrf
                    @if($isEdit)
                        @method('PUT')
                    @endif

                    <div class="form-grid" style="grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));">
                        
                        <div class="form-group">
                            <label for="kode_matakuliah">Mata Kuliah</label>
                            <select name="kode_matakuliah" id="kode_matakuliah" class="form-control @error('kode_matakuliah') is-invalid @enderror" required>
                                <option value="">-- Pilih Mata Kuliah --</option>
                                @foreach($matakuliahs as $mk)
                                    <option value="{{ $mk->kode_matakuliah }}" {{ old('kode_matakuliah', $isEdit ? $editJadwal->kode_matakuliah : '') === $mk->kode_matakuliah ? 'selected' : '' }}>
                                        {{ $mk->nama_matakuliah }} ({{ $mk->kode_matakuliah }})
                                    </option>
                                @endforeach
                            </select>
                            @error('kode_matakuliah')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="nidn">Dosen Pengajar</label>
                            <select name="nidn" id="nidn" class="form-control @error('nidn') is-invalid @enderror" required>
                                <option value="">-- Pilih Dosen Pengajar --</option>
                                @foreach($dosens as $dosen)
                                    <option value="{{ $dosen->nidn }}" {{ old('nidn', $isEdit ? $editJadwal->nidn : '') === $dosen->nidn ? 'selected' : '' }}>
                                        {{ $dosen->nama }} ({{ $dosen->nidn }})
                                    </option>
                                @endforeach
                            </select>
                            @error('nidn')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="kelas">Kelas</label>
                            <input type="text" name="kelas" id="kelas" class="form-control @error('kelas') is-invalid @enderror" 
                                   value="{{ old('kelas', $isEdit ? $editJadwal->kelas : '') }}" 
                                   placeholder="Misal: A / B / C" maxlength="1" required style="text-transform: uppercase;">
                            @error('kelas')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="hari">Hari Kuliah</label>
                            <select name="hari" id="hari" class="form-control @error('hari') is-invalid @enderror" required>
                                <option value="">-- Pilih Hari --</option>
                                @foreach(['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'] as $h)
                                    <option value="{{ $h }}" {{ old('hari', $isEdit ? $editJadwal->hari : '') === $h ? 'selected' : '' }}>
                                        {{ $h }}
                                    </option>
                                @endforeach
                            </select>
                            @error('hari')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="jam">Jam Mulai</label>
                            <input type="time" name="jam" id="jam" class="form-control @error('jam') is-invalid @enderror" 
                                   value="{{ old('jam', ($isEdit && $editJadwal->jam) ? $editJadwal->jam->format('H:i') : '') }}" required>
                            @error('jam')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div style="display: flex; gap: 8px; margin-top: 1.25rem; justify-content: flex-end;">
                        @if($isEdit)
                            <a href="{{ route('jadwal.index') }}" class="btn btn-secondary">Batal</a>
                        @endif
                        <button type="submit" class="btn btn-primary">
                            {{ $isEdit ? 'Simpan Perubahan' : 'Buat Jadwal' }}
                        </button>
                    </div>
                </form>
            @endif
        </div>
    @endif

    <div class="card">
        <div class="search-filter-bar">
            <div class="card-title" style="margin-bottom: 0;">Jadwal Perkuliahan Aktif</div>
            
            <div style="display: flex; gap: 12px; flex-grow: 1; justify-content: flex-end; max-width: 600px; width: 100%; flex-wrap: wrap;">
                
                <form action="{{ route('jadwal.index') }}" method="GET" style="display: flex; gap: 8px;">
                    @if($search)
                        <input type="hidden" name="search" value="{{ $search }}">
                    @endif
                    <select name="hari_filter" class="form-control" onchange="this.form.submit()" style="padding: 8px 12px; height: 40px; min-width: 150px;">
                        <option value="">-- Semua Hari --</option>
                        @foreach(['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'] as $h)
                            <option value="{{ $h }}" {{ $filterHari === $h ? 'selected' : '' }}>
                                {{ $h }}
                            </option>
                        @endforeach
                    </select>
                </form>

                <form action="{{ route('jadwal.index') }}" method="GET" class="search-input-wrapper" style="max-width: 300px;">
                    @if($filterHari)
                        <input type="hidden" name="hari_filter" value="{{ $filterHari }}">
                    @endif
                    <svg class="search-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="11" cy="11" r="8"/>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"/>
                    </svg>
                    <input type="text" name="search" class="form-control" value="{{ $search }}" placeholder="Cari nama mata kuliah atau dosen..." style="height: 40px;">
                </form>
            </div>
        </div>

        @if($jadwals->isEmpty())
            <div style="text-align: center; padding: 3rem 0; color: var(--text-secondary);">
                <p style="font-weight: 500;">Tidak ada jadwal kuliah ditemukan.</p>
                @if($search || $filterHari)
                    <a href="{{ route('jadwal.index') }}" style="color: var(--primary); font-size: 13px; text-decoration: none; margin-top: 8px; display: inline-block;">Reset Filter & Pencarian</a>
                @endif
            </div>
        @else
            <div class="table-container">
                <table class="custom-table">
                    <thead>
                        <tr>
                            <th style="width: 70px;">No</th>
                            <th style="width: 120px;">Hari</th>
                            <th style="width: 140px;">Jam Kuliah</th>
                            <th style="width: 120px;">Kode MK</th>
                            <th>Mata Kuliah (SKS)</th>
                            <th style="width: 100px;">Kelas</th>
                            <th>Dosen Pengajar</th>
                            @if(auth()->user()->isAdmin())
                                <th style="width: 160px; text-align: center;">Aksi</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($jadwals as $index => $jadwal)
                            <tr>
                                <td>{{ $jadwals->firstItem() + $index }}</td>
                                <td><strong>{{ $jadwal->hari }}</strong></td>
                                <td><span class="badge badge-primary">{{ $jadwal->jam ? $jadwal->jam->format('H:i') : '-' }} WIB</span></td>
                                <td><code>{{ $jadwal->kode_matakuliah }}</code></td>
                                <td>
                                    <strong>{{ $jadwal->matakuliah ? $jadwal->matakuliah->nama_matakuliah : '-' }}</strong> 
                                    ({{ $jadwal->matakuliah ? $jadwal->matakuliah->sks : 0 }} SKS)
                                </td>
                                <td><span class="badge badge-success">{{ $jadwal->kelas }}</span></td>
                                <td>{{ $jadwal->dosen ? $jadwal->dosen->nama : '-' }}</td>
                                @if(auth()->user()->isAdmin())
                                    <td>
                                        <div class="actions-cell" style="justify-content: center;">
                                            <a href="{{ route('jadwal.index', ['edit' => $jadwal->id]) }}" class="btn btn-secondary btn-sm">
                                                Ubah
                                            </a>
                                            <form action="{{ route('jadwal.destroy', $jadwal->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus jadwal kuliah ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm">
                                                    Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                @endif
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="pagination-container">
                {{ $jadwals->links() }}
            </div>
        @endif
    </div>
@endsection
