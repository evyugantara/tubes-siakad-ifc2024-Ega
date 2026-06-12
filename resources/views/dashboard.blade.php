@extends('layouts.app')

@section('title', 'Beranda')
@section('header_title', 'Dashboard')
@section('page_title', 'Control Panel')

@section('content')
    @if(auth()->user()->isAdmin())
        
        <div class="stats-grid">
            
            <div class="small-box bg-cyan">
                <div class="inner">
                    <h3>{{ $stats['total_dosen'] }}</h3>
                    <p>Total Dosen Wali</p>
                </div>
                <div class="icon">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                        <circle cx="9" cy="7" r="4"/>
                        <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                        <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                    </svg>
                </div>
                <a href="{{ route('dosen.index') }}" class="small-box-footer">
                    Lihat Detail
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"/>
                        <polyline points="12 8 16 12 12 16"/>
                        <line x1="8" y1="12" x2="16" y2="12"/>
                    </svg>
                </a>
            </div>

            <div class="small-box bg-green">
                <div class="inner">
                    <h3>{{ $stats['total_mahasiswa'] }}</h3>
                    <p>Total Mahasiswa</p>
                </div>
                <div class="icon">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/>
                        <circle cx="9" cy="7" r="4"/>
                        <path d="M22 21v-2a4 4 0 0 0-3-3.87"/>
                        <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                    </svg>
                </div>
                <a href="{{ route('mahasiswa.index') }}" class="small-box-footer">
                    Lihat Detail
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"/>
                        <polyline points="12 8 16 12 12 16"/>
                        <line x1="8" y1="12" x2="16" y2="12"/>
                    </svg>
                </a>
            </div>

            <div class="small-box bg-orange">
                <div class="inner">
                    <h3>{{ $stats['total_matakuliah'] }}</h3>
                    <p>Total Mata Kuliah</p>
                </div>
                <div class="icon">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/>
                        <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/>
                    </svg>
                </div>
                <a href="{{ route('matakuliah.index') }}" class="small-box-footer">
                    Lihat Detail
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"/>
                        <polyline points="12 8 16 12 12 16"/>
                        <line x1="8" y1="12" x2="16" y2="12"/>
                    </svg>
                </a>
            </div>

            <div class="small-box bg-red">
                <div class="inner">
                    <h3>{{ $stats['total_jadwal'] }}</h3>
                    <p>Total Jadwal Kuliah</p>
                </div>
                <div class="icon">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                        <line x1="16" y1="2" x2="16" y2="6"/>
                        <line x1="8" y1="2" x2="8" y2="6"/>
                        <line x1="3" y1="10" x2="21" y2="10"/>
                    </svg>
                </div>
                <a href="{{ route('jadwal.index') }}" class="small-box-footer">
                    Lihat Detail
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"/>
                        <polyline points="12 8 16 12 12 16"/>
                        <line x1="8" y1="12" x2="16" y2="12"/>
                    </svg>
                </a>
            </div>
        </div>

        <div class="box" style="overflow: hidden;">
            <div class="card-header-navy">
                <span>&#9776; Panel Administrasi</span>
            </div>
            
        </div>
    @else
        
        <div class="stats-grid">
            
            <div class="small-box bg-blue" style="grid-column: span 2;">
                <div class="inner" style="padding: 24px 28px;">
                    <h3 style="font-size: 24px; white-space: normal; line-height: 1.2;">{{ $mahasiswa->dosen ? $mahasiswa->dosen->nama : 'Belum Ditentukan' }}</h3>
                    <p style="margin-top: 8px;">Dosen Wali Akademik (NIDN: {{ $mahasiswa->nidn }})</p>
                </div>
                <div class="icon" style="top: 25px; right: 28px;">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="width: 70px; height: 70px;">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                        <circle cx="12" cy="7" r="4"/>
                    </svg>
                </div>
                <div class="small-box-footer" style="padding: 10px;">
                    Profil Mahasiswa: {{ $mahasiswa->nama }} (NPM: {{ $mahasiswa->npm }})
                </div>
            </div>

            <div class="small-box bg-green">
                <div class="inner">
                    <h3>{{ $totalSks }} <span style="font-size: 18px; font-weight: 500;">/ 24</span></h3>
                    <p>Total SKS Terdaftar</p>
                </div>
                <div class="icon">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
                        <polyline points="22 4 12 14.01 9 11.01"/>
                    </svg>
                </div>
                <a href="{{ route('krs.index') }}" class="small-box-footer">
                    Kelola KRS
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"/>
                        <polyline points="12 8 16 12 12 16"/>
                        <line x1="8" y1="12" x2="16" y2="12"/>
                    </svg>
                </a>
            </div>

            <div class="small-box bg-orange">
                <div class="inner">
                    <h3>{{ $totalMatakuliah }}</h3>
                    <p>Mata Kuliah Terdaftar</p>
                </div>
                <div class="icon">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/>
                        <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/>
                    </svg>
                </div>
                <a href="{{ route('krs.index') }}" class="small-box-footer">
                    Kelola KRS
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"/>
                        <polyline points="12 8 16 12 12 16"/>
                        <line x1="8" y1="12" x2="16" y2="12"/>
                    </svg>
                </a>
            </div>
        </div>

        <div class="box" style="overflow: hidden;">
           
            
            <div class="card-body" style="padding: 1.75rem;">
                @if($personalJadwal->isEmpty())
                    <div style="text-align: center; padding: 3rem 0; color: var(--text-secondary);">
                        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="margin-bottom: 1rem; color: var(--border);">
                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                            <line x1="16" y1="2" x2="16" y2="6"/>
                            <line x1="8" y1="2" x2="8" y2="6"/>
                            <line x1="3" y1="10" x2="21" y2="10"/>
                        </svg>
                        <p style="font-weight: 500;">Jadwal kuliah belum tersedia.</p>
                        <p style="font-size: 13px; margin-top: 4px;">Anda belum mengambil mata kuliah di KRS atau jadwal pengajar belum ditentukan.</p>
                    </div>
                @else
                    <div class="table-container">
                        <table class="custom-table">
                            <thead>
                                <tr>
                                    <th>Hari</th>
                                    <th>Waktu</th>
                                    <th>Kode MK</th>
                                    <th>Mata Kuliah</th>
                                    <th>SKS</th>
                                    <th>Kelas</th>
                                    <th>Dosen Pengajar</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($personalJadwal as $jadwal)
                                    <tr>
                                        <td><strong>{{ $jadwal->hari }}</strong></td>
                                        <td><span class="badge badge-primary">{{ $jadwal->jam ? $jadwal->jam->format('H:i') : '-' }} WIB</span></td>
                                        <td><code>{{ $jadwal->kode_matakuliah }}</code></td>
                                        <td>{{ $jadwal->matakuliah ? $jadwal->matakuliah->nama_matakuliah : '-' }}</td>
                                        <td>{{ $jadwal->matakuliah ? $jadwal->matakuliah->sks : 0 }} SKS</td>
                                        <td><span class="badge badge-success">{{ $jadwal->kelas }}</span></td>
                                        <td>{{ $jadwal->dosen ? $jadwal->dosen->nama : '-' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    @endif
@endsection
