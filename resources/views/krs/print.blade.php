<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak KRS - {{ $mahasiswa->npm }}</title>
    <style>
        body {
            background-color: white;
            color: black;
            font-family: 'Times New Roman', Times, serif;
            font-size: 11pt;
            line-height: 1.4;
            padding: 30px;
            margin: 0;
        }

        /* Kop Surat */
        .kop-surat {
            text-align: center;
            border-bottom: 3px double black;
            padding-bottom: 12px;
            margin-bottom: 25px;
        }

        .kop-surat h1 {
            font-size: 16pt;
            font-weight: bold;
            margin: 0 0 5px 0;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .kop-surat h2 {
            font-size: 14pt;
            font-weight: bold;
            margin: 0 0 5px 0;
            text-transform: uppercase;
        }

        .kop-surat p {
            font-size: 10pt;
            margin: 2px 0;
            font-style: italic;
        }

        .document-title {
            text-align: center;
            font-size: 13pt;
            font-weight: bold;
            text-transform: uppercase;
            text-decoration: underline;
            margin-bottom: 25px;
        }

        /* Informasi Mahasiswa */
        .info-table {
            width: 100%;
            margin-bottom: 20px;
            border-collapse: collapse;
        }

        .info-table td {
            padding: 4px 0;
            vertical-align: top;
        }

        .info-table td.label {
            width: 15%;
            font-weight: bold;
        }

        .info-table td.separator {
            width: 3%;
            text-align: center;
        }

        .info-table td.value {
            width: 32%;
        }

        /* Tabel KRS */
        .krs-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }

        .krs-table th, .krs-table td {
            border: 1px solid black;
            padding: 8px 10px;
            text-align: left;
        }

        .krs-table th {
            background-color: #f2f2f2;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 10pt;
            text-align: center;
        }

        .krs-table td.center {
            text-align: center;
        }

        .krs-table .total-row {
            font-weight: bold;
            background-color: #fafafa;
        }

        /* Tanda Tangan */
        .signature-section {
            width: 100%;
            margin-top: 50px;
            page-break-inside: avoid;
        }

        .signature-table {
            width: 100%;
            border-collapse: collapse;
        }

        .signature-table td {
            width: 50%;
            text-align: center;
            vertical-align: top;
        }

        .signature-title {
            margin-bottom: 70px;
        }

        .signature-name {
            font-weight: bold;
            text-decoration: underline;
        }

        .signature-id {
            margin-top: 4px;
            font-size: 10pt;
        }

        /* CSS khusus cetak */
        @media print {
            body {
                padding: 15px;
            }
            .no-print {
                display: none;
            }
        }
        
        .no-print-bar {
            background-color: #f3f4f6;
            border: 1px solid #d1d5db;
            padding: 10px 20px;
            margin-bottom: 20px;
            border-radius: 6px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-family: sans-serif;
            font-size: 13px;
            color: #374151;
        }
        
        .no-print-bar button {
            background-color: #4f46e5;
            color: white;
            border: none;
            padding: 6px 14px;
            border-radius: 4px;
            font-weight: bold;
            cursor: pointer;
        }
        
        .no-print-bar button:hover {
            background-color: #4338ca;
        }
    </style>
</head>
<body>
    
    <div class="no-print no-print-bar">
        <span>Halaman Cetak KRS Mahasiswa (Gunakan Ctrl+P jika dialog cetak tidak otomatis terbuka).</span>
        <button onclick="window.print()">Cetak Dokumen</button>
    </div>

    <div class="kop-surat">
        <h1>UNIVERSITAS SURYAKENCANA</h1>
        <h2>FAKULTAS TEKNIK - PROGRAM STUDI TEKNIK INFORMATIKA</h2>
        <p>Jl. Pasir Gede Raya, Cianjur, Jawa Barat. Telp: (0263) 283578</p>
    </div>

    <div class="document-title">
        KARTU RENCANA STUDI (KRS)
    </div>

    <table class="info-table">
        <tr>
            <td class="label">Nama Mahasiswa</td>
            <td class="separator">:</td>
            <td class="value"><strong>{{ $mahasiswa->nama }}</strong></td>
            
            <td class="label">Tahun Akademik</td>
            <td class="separator">:</td>
            <td class="value">2026/2027 Genap</td>
        </tr>
        <tr>
            <td class="label">NPM</td>
            <td class="separator">:</td>
            <td class="value"><code>{{ $mahasiswa->npm }}</code></td>
            
            <td class="label">Program Studi</td>
            <td class="separator">:</td>
            <td class="value">Teknik Informatika</td>
        </tr>
        <tr>
            <td class="label">Dosen Wali</td>
            <td class="separator">:</td>
            <td class="value">{{ $mahasiswa->dosen ? $mahasiswa->dosen->nama : '-' }}</td>
            
            <td class="label">NIDN Wali</td>
            <td class="separator">:</td>
            <td class="value"><code>{{ $mahasiswa->nidn }}</code></td>
        </tr>
    </table>

    <table class="krs-table">
        <thead>
            <tr>
                <th style="width: 6%;">No</th>
                <th style="width: 20%;">Kode MK</th>
                <th>Nama Mata Kuliah</th>
                <th style="width: 12%;">SKS</th>
                <th style="width: 25%;">Tanda Tangan Dosen Wali</th>
            </tr>
        </thead>
        <tbody>
            @forelse($krsList as $index => $item)
                <tr>
                    <td class="center">{{ $index + 1 }}</td>
                    <td class="center"><code>{{ $item->kode_matakuliah }}</code></td>
                    <td>{{ $item->matakuliah ? $item->matakuliah->nama_matakuliah : '-' }}</td>
                    <td class="center">{{ $item->matakuliah ? $item->matakuliah->sks : 0 }} SKS</td>
                    <td></td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="text-align: center; font-style: italic; padding: 20px;">Belum mengambil mata kuliah.</td>
                </tr>
            @endforelse
            
            <tr class="total-row">
                <td colspan="3" style="text-align: right; padding-right: 15px;">TOTAL SKS YANG DIAMBIL:</td>
                <td class="center">{{ $totalSks }} SKS</td>
                <td></td>
            </tr>
        </tbody>
    </table>

    <div class="signature-section">
        <table class="signature-table">
            <tr>
                <td>
                    <div class="signature-title">
                        Cianjur, {{ date('d F Y') }}<br>
                        Mahasiswa Yang Bersangkutan,
                    </div>
                    <div class="signature-name">{{ $mahasiswa->nama }}</div>
                    <div class="signature-id">NPM. {{ $mahasiswa->npm }}</div>
                </td>
                <td>
                    <div class="signature-title">
                        Mengetahui,<br>
                        Dosen Wali Akademik,
                    </div>
                    <div class="signature-name">{{ $mahasiswa->dosen ? $mahasiswa->dosen->nama : '................................................' }}</div>
                    <div class="signature-id">NIDN. {{ $mahasiswa->nidn }}</div>
                </td>
            </tr>
        </table>
    </div>

    <script>
        window.addEventListener('DOMContentLoaded', () => {
            setTimeout(() => {
                window.print();
            }, 500);
        });
    </script>
</body>
</html>
