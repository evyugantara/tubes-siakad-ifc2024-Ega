@extends('layouts.app')

@section('title', 'Kartu Rencana Studi (KRS)')
@section('header_title', 'Kartu Rencana Studi (KRS)')
@section('header_subtitle', 'Pengelolaan rencana studi mahasiswa Universitas Suryakencana')

@section('content')
    @if(auth()->user()->isAdmin())
        
        <div class="card">
            <div class="search-filter-bar">
                <div class="card-title" style="margin-bottom: 0;">Monitoring KRS Mahasiswa</div>
                
                <div style="display: flex; gap: 12px; flex-grow: 1; justify-content: flex-end; max-width: 700px; width: 100%; flex-wrap: wrap;">
                    
                    <form action="{{ route('krs.index') }}" method="GET" style="display: flex; gap: 8px;">
                        @if($search)
                            <input type="hidden" name="search" value="{{ $search }}">
                        @endif
                        <select name="mahasiswa_filter" class="form-control" onchange="this.form.submit()" style="padding: 8px 12px; height: 40px; min-width: 250px;">
                            <option value="">-- Pilih Mahasiswa --</option>
                            @foreach($mahasiswas as $mhs)
                                <option value="{{ $mhs->npm }}" {{ $filterMahasiswa === $mhs->npm ? 'selected' : '' }}>
                                    {{ $mhs->npm }} - {{ $mhs->nama }}
                                </option>
                            @endforeach
                        </select>
                    </form>

                    <form action="{{ route('krs.index') }}" method="GET" class="search-input-wrapper" style="max-width: 300px;">
                        @if($filterMahasiswa)
                            <input type="hidden" name="mahasiswa_filter" value="{{ $filterMahasiswa }}">
                        @endif
                        <svg class="search-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="11" cy="11" r="8"/>
                            <line x1="21" y1="21" x2="16.65" y2="16.65"/>
                        </svg>
                        <input type="text" name="search" class="form-control" value="{{ $search }}" placeholder="Cari nama atau NPM..." style="height: 40px;">
                    </form>
                </div>
            </div>

            @if($krsList->isEmpty())
                <div style="text-align: center; padding: 3rem 0; color: var(--text-secondary);">
                    <p style="font-weight: 500;">Tidak ada pengambilan KRS ditemukan.</p>
                    @if($search || $filterMahasiswa)
                        <a href="{{ route('krs.index') }}" style="color: var(--primary); font-size: 13px; text-decoration: none; margin-top: 8px; display: inline-block;">Reset Filter & Pencarian</a>
                    @endif
                </div>
            @else
                <div class="table-container">
                    <table class="custom-table">
                        <thead>
                            <tr>
                                <th style="width: 70px;">No</th>
                                <th style="width: 150px;">NPM</th>
                                <th>Nama Mahasiswa</th>
                                <th>Dosen Wali</th>
                                <th style="width: 120px;">Kode MK</th>
                                <th>Mata Kuliah Pilihan</th>
                                <th style="width: 100px;">SKS</th>
                                <th style="width: 130px; text-align: center;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($krsList as $index => $item)
                                <tr>
                                    <td>{{ $krsList->firstItem() + $index }}</td>
                                    <td><code>{{ $item->npm }}</code></td>
                                    <td><strong>{{ $item->mahasiswa ? $item->mahasiswa->nama : '-' }}</strong></td>
                                    <td>
                                        <span style="font-size: 13px; font-weight: 500;">
                                            {{ ($item->mahasiswa && $item->mahasiswa->dosen) ? $item->mahasiswa->dosen->nama : '-' }}
                                        </span>
                                    </td>
                                    <td><code>{{ $item->kode_matakuliah }}</code></td>
                                    <td>
                                        {{ $item->matakuliah ? $item->matakuliah->nama_matakuliah : '-' }}
                                    </td>
                                    <td><span class="badge badge-primary">{{ $item->matakuliah ? $item->matakuliah->sks : 0 }} SKS</span></td>
                                    <td>
                                        <div class="actions-cell" style="justify-content: center;">
                                            
                                            <form action="{{ route('krs.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan (drop) mata kuliah ini untuk mahasiswa tersebut?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm">
                                                    Batalkan
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
                    {{ $krsList->links() }}
                </div>
            @endif
        </div>
    @else
        
        <div class="card inline-form-card">
            <div class="card-title">Input Kartu Rencana Studi (KRS)</div>
            
            @if($availableMatakuliahs->isEmpty())
                <div class="alert alert-success" style="margin-bottom: 0; padding: 12px 16px;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 8px;">
                        <circle cx="12" cy="12" r="10"/>
                        <polyline points="12 6 12 12 16 14"/>
                    </svg>
                    <span>Luar biasa! Semua mata kuliah yang ditawarkan pada semester ini sudah Anda ambil.</span>
                </div>
            @else
                <form action="{{ route('krs.store') }}" method="POST">
                    @csrf
                    <div style="display: flex; gap: 12px; align-items: flex-end; flex-wrap: wrap;">
                        
                        <div class="form-group" style="flex-grow: 1; margin-bottom: 0;">
                            <label for="kode_matakuliah">Pilih Mata Kuliah yang Ditawarkan</label>
                            <select name="kode_matakuliah" id="kode_matakuliah" class="form-control @error('kode_matakuliah') is-invalid @enderror" required style="width: 100%; height: 44px;">
                                <option value="">-- Pilih Mata Kuliah --</option>
                                @foreach($availableMatakuliahs as $mk)
                                    <option value="{{ $mk->kode_matakuliah }}" {{ old('kode_matakuliah') === $mk->kode_matakuliah ? 'selected' : '' }}>
                                        {{ $mk->nama_matakuliah }} ({{ $mk->kode_matakuliah }} - {{ $mk->sks }} SKS)
                                    </option>
                                @endforeach
                            </select>
                            @error('kode_matakuliah')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary" style="height: 44px; padding: 0 24px;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="12" y1="5" x2="12" y2="19"/>
                                <line x1="5" y1="12" x2="19" y2="12"/>
                            </svg>
                            Ambil Mata Kuliah
                        </button>
                    </div>
                </form>
            @endif
        </div>

        <div class="card">
            <div class="search-filter-bar">
                <div>
                    <div class="card-title" style="margin-bottom: 4px;">Rencana Studi Terdaftar</div>
                    <p style="font-size: 13px; color: var(--text-secondary);">Dosen Wali: <strong>{{ $mahasiswa->dosen ? $mahasiswa->dosen->nama : '-' }}</strong> (NIDN: {{ $mahasiswa->nidn }})</p>
                </div>

                <div style="display: flex; gap: 8px;">
                    
                    <div class="badge {{ $totalSks > 24 ? 'badge-danger' : 'badge-primary' }}" style="padding: 10px 16px; font-size: 14px; font-weight: 700;">
                        Beban SKS: {{ $totalSks }} / 24 SKS
                    </div>

                    @if($krsList->isNotEmpty())
                        
                        <a href="{{ route('krs.export') }}" class="btn btn-secondary btn-sm" style="height: 38px;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                                <polyline points="7 10 12 15 17 10"/>
                                <line x1="12" y1="15" x2="12" y2="3"/>
                            </svg>
                            Excel (CSV)
                        </a>

                        <a href="{{ route('krs.print') }}" target="_blank" class="btn btn-primary btn-sm" style="height: 38px;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="6 9 6 2 18 2 18 9"/>
                                <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/>
                                <rect x="6" y="14" width="12" height="8"/>
                            </svg>
                            Cetak KRS
                        </a>
                    @endif
                </div>
            </div>

            @if($krsList->isEmpty())
                <div style="text-align: center; padding: 4rem 0; color: var(--text-secondary);">
                    <svg xmlns="http://www.w3.org/2000/svg" width="54" height="54" viewBox="0 0 24 24" fill="none" stroke="var(--border)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="margin-bottom: 1rem;">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                        <polyline points="14 2 14 8 20 8"/>
                    </svg>
                    <p style="font-weight: 600; font-size: 15px;">Belum ada mata kuliah yang diambil.</p>
                    <p style="font-size: 13px; margin-top: 4px;">Silakan pilih mata kuliah di form atas untuk didaftarkan ke KRS Anda.</p>
                </div>
            @else
                <div class="table-container">
                    <table class="custom-table">
                        <thead>
                            <tr>
                                <th style="width: 70px;">No</th>
                                <th style="width: 200px;">Kode MK</th>
                                <th>Nama Mata Kuliah</th>
                                <th style="width: 150px;">Bobot SKS</th>
                                <th style="width: 150px; text-align: center;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($krsList as $index => $item)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td><code>{{ $item->kode_matakuliah }}</code></td>
                                    <td><strong>{{ $item->matakuliah ? $item->matakuliah->nama_matakuliah : '-' }}</strong></td>
                                    <td><span class="badge badge-primary">{{ $item->matakuliah ? $item->matakuliah->sks : 0 }} SKS</span></td>
                                    <td>
                                        <div class="actions-cell" style="justify-content: center;">
                                            
                                            <form action="{{ route('krs.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan (drop) pengambilan mata kuliah ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm">
                                                    Drop
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    @endif
@endsection
