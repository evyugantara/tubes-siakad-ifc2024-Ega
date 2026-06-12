<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Dosen;
use App\Models\Mahasiswa;
use App\Models\MataKuliah;
use App\Models\Jadwal;
use App\Models\Krs;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    
    public function run(): void
    {
        
        User::create([
            'name' => 'Administrator SIAKAD',
            'email' => 'admin@siakad.ac.id',
            'username' => 'admin',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'npm' => null,
        ]);

        $dosens = [
            ['nidn' => '0408017501', 'nama' => 'Dr. Ir. H. Mgs. Hendri, M.T.'],
            ['nidn' => '0412038102', 'nama' => 'Priyatna, S.T., M.T.'],
            ['nidn' => '0415058703', 'nama' => 'Rani Susanto, S.Kom., M.Kom.'],
            ['nidn' => '0422119004', 'nama' => 'Sandhika Galih, S.T., M.T.'],
        ];

        foreach ($dosens as $dosen) {
            Dosen::create($dosen);
        }

        $mahasiswas = [
            ['npm' => '223040001', 'nama' => 'Ahmad Fauzi', 'nidn' => '0422119004'], 
            ['npm' => '223040002', 'nama' => 'Siti Aminah', 'nidn' => '0412038102'], 
            ['npm' => '223040003', 'nama' => 'Rizky Pratama', 'nidn' => '0415058703'], 
        ];

        foreach ($mahasiswas as $mhs) {
            Mahasiswa::create($mhs);

            User::create([
                'name' => $mhs['nama'],
                'email' => $mhs['npm'] . '@student.unsur.ac.id',
                'username' => $mhs['npm'],
                'password' => Hash::make($mhs['npm']), 
                'role' => 'mahasiswa',
                'npm' => $mhs['npm'],
            ]);
        }

        $matakuliahs = [
            ['kode_matakuliah' => 'IF53413', 'nama_matakuliah' => 'Pemrograman Web II', 'sks' => 3],
            ['kode_matakuliah' => 'IF53414', 'nama_matakuliah' => 'Pemrograman Mobile', 'sks' => 3],
            ['kode_matakuliah' => 'IF53415', 'nama_matakuliah' => 'Rekayasa Perangkat Lunak', 'sks' => 3],
            ['kode_matakuliah' => 'IF53416', 'nama_matakuliah' => 'Kecerdasan Buatan', 'sks' => 3],
            ['kode_matakuliah' => 'IF53417', 'nama_matakuliah' => 'Basis Data Lanjut', 'sks' => 3],
            ['kode_matakuliah' => 'IF53418', 'nama_matakuliah' => 'Keamanan Informasi', 'sks' => 2],
            ['kode_matakuliah' => 'IF53419', 'nama_matakuliah' => 'Etika Profesi IT', 'sks' => 2],
        ];

        foreach ($matakuliahs as $mk) {
            MataKuliah::create($mk);
        }

        $jadwals = [
            [
                'kode_matakuliah' => 'IF53413',
                'nidn' => '0422119004', 
                'kelas' => 'A',
                'hari' => 'Senin',
                'jam' => Carbon::createFromFormat('H:i', '08:00')->setDate(2026, 1, 1)->toDateTimeString()
            ],
            [
                'kode_matakuliah' => 'IF53413',
                'nidn' => '0422119004', 
                'kelas' => 'B',
                'hari' => 'Senin',
                'jam' => Carbon::createFromFormat('H:i', '10:00')->setDate(2026, 1, 1)->toDateTimeString()
            ],
            [
                'kode_matakuliah' => 'IF53414',
                'nidn' => '0412038102', 
                'kelas' => 'A',
                'hari' => 'Selasa',
                'jam' => Carbon::createFromFormat('H:i' ,'08:00')->setDate(2026, 1, 1)->toDateTimeString()
            ],
            [
                'kode_matakuliah' => 'IF53415',
                'nidn' => '0415058703', 
                'kelas' => 'A',
                'hari' => 'Rabu',
                'jam' => Carbon::createFromFormat('H:i' ,'13:00')->setDate(2026, 1, 1)->toDateTimeString()
            ],
            [
                'kode_matakuliah' => 'IF53416',
                'nidn' => '0408017501', 
                'kelas' => 'A',
                'hari' => 'Kamis',
                'jam' => Carbon::createFromFormat('H:i' ,'10:00')->setDate(2026, 1, 1)->toDateTimeString()
            ],
            [
                'kode_matakuliah' => 'IF53417',
                'nidn' => '0412038102', 
                'kelas' => 'B',
                'hari' => 'Jumat',
                'jam' => Carbon::createFromFormat('H:i' ,'08:30')->setDate(2026, 1, 1)->toDateTimeString()
            ],
        ];

        foreach ($jadwals as $jadwal) {
            Jadwal::create($jadwal);
        }

        Krs::create(['npm' => '223040001', 'kode_matakuliah' => 'IF53413']);
        Krs::create(['npm' => '223040001', 'kode_matakuliah' => 'IF53414']);

        Krs::create(['npm' => '223040002', 'kode_matakuliah' => 'IF53415']);
        Krs::create(['npm' => '223040002', 'kode_matakuliah' => 'IF53416']);
    }
}
