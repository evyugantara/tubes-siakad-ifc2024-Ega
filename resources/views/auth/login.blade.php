<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk - SIAKAD FT UNSUR</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Source Sans Pro', 'Segoe UI', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
            background: linear-gradient(135deg, #1a3a5c 0%, #2d6a9f 40%, #3c8dbc 70%, #00c0ef 100%);
        }

        /* ===== DEKORASI BACKGROUND ===== */
        /* Lingkaran besar transparan */
        .bg-circle {
            position: absolute;
            border-radius: 50%;
            background: rgba(255,255,255,0.05);
            pointer-events: none;
        }
        .bg-circle-1 { width: 600px; height: 600px; top: -200px; left: -180px; }
        .bg-circle-2 { width: 400px; height: 400px; bottom: -150px; right: -100px; background: rgba(255,255,255,0.04); }
        .bg-circle-3 { width: 250px; height: 250px; top: 40%; right: 10%; background: rgba(255,255,255,0.03); }

        /* Icon SVG Akademik mengambang di background */
        .bg-icon {
            position: absolute;
            opacity: 0.07;
            pointer-events: none;
            color: #fff;
        }

        .bg-icon svg { width: 100%; height: 100%; stroke: white; fill: none; stroke-width: 1.5; }

        /* Posisi dan ukuran masing-masing icon */
        .bg-icon-1  { width: 140px; height: 140px; top: 5%;    left: 5%; }
        .bg-icon-2  { width: 110px; height: 110px; top: 10%;   right: 8%; }
        .bg-icon-3  { width: 160px; height: 160px; bottom: 8%; left: 3%; }
        .bg-icon-4  { width: 120px; height: 120px; bottom: 5%; right: 6%; }
        .bg-icon-5  { width: 90px;  height: 90px;  top: 45%;   left: 8%; opacity: 0.05; }
        .bg-icon-6  { width: 100px; height: 100px; top: 30%;   right: 4%; opacity: 0.05; }
        .bg-icon-7  { width: 80px;  height: 80px;  bottom: 30%; left: 18%; opacity: 0.04; }
        .bg-icon-8  { width: 70px;  height: 70px;  top: 20%;   left: 25%; opacity: 0.04; }

        /* Teks info di pojok kiri bawah */
        .bg-watermark {
            position: absolute;
            bottom: 30px;
            left: 40px;
            color: rgba(255,255,255,0.2);
            font-size: 13px;
            font-family: inherit;
            letter-spacing: 0.5px;
            pointer-events: none;
        }

        .bg-watermark strong {
            display: block;
            font-size: 18px;
            font-weight: 700;
            letter-spacing: 1px;
            margin-bottom: 3px;
            color: rgba(255,255,255,0.25);
        }

        /* ===== CARD LOGIN ===== */
        .login-card {
            position: relative;
            z-index: 10;
            width: 100%;
            max-width: 400px;
            background: #fff;
            border-radius: 6px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.35), 0 2px 10px rgba(0,0,0,0.2);
            overflow: hidden;
        }

        .login-header {
            background: linear-gradient(135deg, #3c8dbc, #2176ae);
            padding: 28px 30px 24px;
            text-align: center;
            color: #fff;
        }

        .login-header .logo-wrapper {
            width: 64px; height: 64px;
            border-radius: 50%;
            background: rgba(255,255,255,0.2);
            border: 2px solid rgba(255,255,255,0.4);
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 14px;
        }

        .login-header .logo-wrapper svg { width: 32px; height: 32px; }
        .login-header h1 { font-size: 22px; font-weight: 700; letter-spacing: 0.5px; }
        .login-header p  { font-size: 13px; opacity: 0.85; margin-top: 4px; }

        .login-body { padding: 28px 30px 30px; }

        .form-group { margin-bottom: 16px; }

        .form-group label {
            display: block;
            font-size: 13px;
            font-weight: 700;
            color: #555;
            margin-bottom: 6px;
        }

        .form-group input {
            width: 100%;
            padding: 9px 13px;
            border: 1.5px solid #dde1e7;
            border-radius: 4px;
            font-size: 14px;
            font-family: inherit;
            color: #333;
            background: #fafbfc;
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        .form-group input:focus {
            border-color: #3c8dbc;
            box-shadow: 0 0 0 3px rgba(60,141,188,0.15);
            background: #fff;
        }

        .form-group input.is-invalid { border-color: #e74c3c; }
        .error-msg { font-size: 12px; color: #e74c3c; margin-top: 4px; }

        .remember-row {
            display: flex;
            align-items: center;
            gap: 8px;
            margin: 6px 0 22px;
        }

        .remember-row input[type="checkbox"] {
            width: 15px; height: 15px;
            accent-color: #3c8dbc; cursor: pointer;
        }

        .remember-row label { font-size: 13px; color: #666; cursor: pointer; }

        .btn-login {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            width: 100%;
            padding: 11px;
            background: #3c8dbc;
            color: #fff;
            border: none;
            border-radius: 4px;
            font-size: 15px;
            font-weight: 700;
            font-family: inherit;
            cursor: pointer;
            transition: background 0.2s;
        }

        .btn-login:hover { background: #367fa9; }
        .btn-login svg { width: 17px; height: 17px; }

        .alert-success-login {
            background: #d4edda;
            border: 1px solid #c3e6cb;
            color: #155724;
            border-radius: 4px;
            padding: 9px 13px;
            font-size: 13px;
            margin-bottom: 16px;
        }
    </style>
</head>
<body>

    {{-- ===== DEKORASI BACKGROUND AKADEMIK ===== --}}

    {{-- Lingkaran --}}
    <div class="bg-circle bg-circle-1"></div>
    <div class="bg-circle bg-circle-2"></div>
    <div class="bg-circle bg-circle-3"></div>

    {{-- Icon 1: Toga / Wisuda --}}
    <div class="bg-icon bg-icon-1">
        <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path d="M22 10v6M2 10l10-5 10 5-10 5z"/>
            <path d="M6 12v5c0 2 2 3 6 3s6-1 6-3v-5"/>
        </svg>
    </div>

    {{-- Icon 2: Buku --}}
    <div class="bg-icon bg-icon-2">
        <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/>
            <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/>
        </svg>
    </div>

    {{-- Icon 3: Kalender / Jadwal --}}
    <div class="bg-icon bg-icon-3">
        <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
            <line x1="16" y1="2" x2="16" y2="6"/>
            <line x1="8" y1="2" x2="8" y2="6"/>
            <line x1="3" y1="10" x2="21" y2="10"/>
        </svg>
    </div>

    {{-- Icon 4: KRS / Dokumen --}}
    <div class="bg-icon bg-icon-4">
        <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
            <polyline points="14 2 14 8 20 8"/>
            <line x1="16" y1="13" x2="8" y2="13"/>
            <line x1="16" y1="17" x2="8" y2="17"/>
            <polyline points="10 9 9 9 8 9"/>
        </svg>
    </div>

    {{-- Icon 5: Pengguna / Mahasiswa --}}
    <div class="bg-icon bg-icon-5">
        <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
            <circle cx="9" cy="7" r="4"/>
            <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
            <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
        </svg>
    </div>

    {{-- Icon 6: Grafik / Nilai --}}
    <div class="bg-icon bg-icon-6">
        <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <line x1="18" y1="20" x2="18" y2="10"/>
            <line x1="12" y1="20" x2="12" y2="4"/>
            <line x1="6" y1="20" x2="6" y2="14"/>
            <line x1="2" y1="20" x2="22" y2="20"/>
        </svg>
    </div>

    {{-- Icon 7: Pensil --}}
    <div class="bg-icon bg-icon-7">
        <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path d="M12 20h9"/>
            <path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/>
        </svg>
    </div>

    {{-- Icon 8: Bintang / Prestasi --}}
    <div class="bg-icon bg-icon-8">
        <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
        </svg>
    </div>

    {{-- Watermark teks --}}
    <div class="bg-watermark">
        <strong>SIAKAD FT UNSUR</strong>
        Sistem Informasi Akademik<br>Universitas Suryakencana
    </div>

    {{-- ===== CARD LOGIN ===== --}}
    <div class="login-card">

        <div class="login-header">
            <div class="logo-wrapper">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M22 10v6M2 10l10-5 10 5-10 5z"/>
                    <path d="M6 12v5c0 2 2 3 6 3s6-1 6-3v-5"/>
                </svg>
            </div>
            <h1>SIAKAD FT UNSUR</h1>
            <p>Sistem Informasi Akademik Sederhana</p>
        </div>

        <div class="login-body">

            @if(session('success'))
                <div class="alert-success-login">
                    ✓ {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('login') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label for="username">Username atau NPM</label>
                    <input
                        type="text"
                        name="username"
                        id="username"
                        class="{{ $errors->has('username') ? 'is-invalid' : '' }}"
                        value="{{ old('username') }}"
                        autocomplete="username"
                        placeholder="Masukkan username atau NPM"
                        required autofocus
                    >
                    @error('username')
                        <div class="error-msg">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password">Kata Sandi</label>
                    <input
                        type="password"
                        name="password"
                        id="password"
                        class="{{ $errors->has('password') ? 'is-invalid' : '' }}"
                        placeholder="••••••••"
                        required
                    >
                    @error('password')
                        <div class="error-msg">{{ $message }}</div>
                    @enderror
                </div>

                <div class="remember-row">
                    <input type="checkbox" name="remember" id="remember">
                    <label for="remember">Ingat saya di perangkat ini</label>
                </div>

                <button type="submit" class="btn-login">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/>
                        <polyline points="10 17 15 12 10 7"/>
                        <line x1="15" y1="12" x2="3" y2="12"/>
                    </svg>
                    Masuk Sistem
                </button>

            </form>
        </div>
    </div>

</body>
</html>
