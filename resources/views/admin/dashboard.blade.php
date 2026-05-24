@extends('layouts.admin')

@section('content')

<script src="https://cdn.tailwindcss.com"></script>

<style>
    /* Font Global Bindings */
    .font-anime-header {
        font-family: 'Fredoka One', cursive, sans-serif !important;
    }
    .font-anime-body {
        font-family: 'Nunito', sans-serif !important;
    }

    /* Fallback Tata Letak */
    .flex { display: flex !important; }
    .flex-col { flex-direction: column !important; }
    .flex-row { flex-direction: row !important; }
    .flex-wrap { flex-wrap: wrap !important; }
    .justify-between { justify-content: space-between !important; }
    .items-center { align-items: center !important; }
    .inline-block { display: inline-block !important; }
    
    .grid { display: grid !important; }
    .grid-cols-1 { grid-template-columns: repeat(1, minmax(0, 1fr)) !important; }
    
    /* Spacing & Ukuran Standar */
    .p-8 { padding: 32px !important; }
    .p-6 { padding: 24px !important; }
    .p-4 { padding: 16px !important; }
    .px-4 { padding-left: 16px !important; padding-right: 16px !important; }
    .py-2 { padding-top: 8px !important; padding-bottom: 8px !important; }
    .px-3 { padding-left: 12px !important; padding-right: 12px !important; }
    .py-1 { padding-top: 4px !important; padding-bottom: 4px !important; }
    .py-1\.5 { padding-top: 6px !important; padding-bottom: 6px !important; }
    
    .mb-8 { margin-bottom: 32px !important; }
    .mb-6 { margin-bottom: 24px !important; }
    .mb-4 { margin-bottom: 16px !important; }
    .mb-3 { margin-bottom: 12px !important; }
    .mb-1\.5 { margin-bottom: 6px !important; }
    .mt-4 { margin-top: 16px !important; }
    .mt-3 { margin-top: 12px !important; }
    
    .gap-6 { gap: 24px !important; }
    .gap-4 { gap: 16px !important; }
    .gap-3 { gap: 12px !important; }
    
    .w-full { width: 100% !important; }
    .h-full { height: 100% !important; }
    .h-48 { height: 192px !important; }
    .flex-1 { flex: 1 1 0% !important; }

    /* Breakpoint Grid responsif manual */
    @media (min-width: 640px) {
        .sm\:grid-cols-2 { grid-template-columns: repeat(2, minmax(0, 1fr)) !important; }
        .sm\:flex-row { flex-direction: row !important; }
    }
    @media (min-width: 1024px) {
        .lg\:grid-cols-4 { grid-template-columns: repeat(4, minmax(0, 1fr)) !important; }
    }

    /* Ukuran Teks Fallback */
    .text-xs { font-size: 12px !important; }
    .text-sm { font-size: 14px !important; }
    .text-base { font-size: 16px !important; }
    .text-lg { font-size: 18px !important; }
    .text-xl { font-size: 20px !important; }
    .text-4xl { font-size: 32px !important; }
    .text-5xl { font-size: 40px !important; }

    /* Struktur Utama Neobrutalism */
    .neo-card {
        background: #FFFFFF;
        border: 3px solid #1E1B29 !important;
        box-shadow: 6px 6px 0px 0px #1E1B29 !important;
        border-radius: 16px !important;
        transition: all 0.2s ease;
        position: relative;
        
    }

    .neo-card:hover {
        transform: translate(-2px, -2px);
        box-shadow: 8px 8px 0px 0px #1E1B29 !important;
    }

    .neo-btn {
        font-family: 'Fredoka One', cursive, sans-serif;
        border: 3px solid #1E1B29 !important;
        box-shadow: 3px 3px 0px 0px #1E1B29 !important;
        transition: all 0.15s ease;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .neo-btn:hover {
        transform: translate(-1px, -1px);
        box-shadow: 4px 4px 0px 0px #1E1B29 !important;
    }

    .neo-btn:active {
        transform: translate(2px, 2px);
        box-shadow: 1px 1px 0px 0px #1E1B29 !important;
    }

    /* Tabel Gaya Manga */
    .neo-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
    }

    .neo-table th {
        background-color: #FAF9FF;
        border-bottom: 3px solid #1E1B29;
        font-family: 'Fredoka One', sans-serif;
        color: #1E1B29;
        text-transform: uppercase;
        font-size: 13px;
        letter-spacing: 0.5px;
        padding: 16px;
    }

    .neo-table td {
        padding: 16px;
        border-bottom: 2px solid #1E1B29;
        font-family: 'Nunito', sans-serif;
        font-weight: 700;
        color: #1E1B29;
    }

    .neo-table tbody tr:last-child td {
        border-bottom: none;
    }

    /* Lencana Status Anime */
    .badge-anime {
        display: inline-block;
        padding: 6px 12px;
        font-family: 'Fredoka One', sans-serif;
        font-size: 11px;
        text-transform: uppercase;
        border: 2px solid #1E1B29;
        border-radius: 8px;
        box-shadow: 2px 2px 0px 0px #1E1B29;
    }

    .badge-anime-hadir {
        background-color: #00CEC9; /* Cyber Oasis */
        color: #1E1B29;
    }

    .badge-anime-absen {
        background-color: #FF7675; /* Sakura Burst */
        color: #FFFFFF;
    }

    /* Kisi Layout Kolom */
    .neo-layout {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 32px;
        align-items: start;
    }

    @media (max-width: 1024px) {
        .neo-layout {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="font-anime-body text-[#1E1B29] min-h-screen w-full -m-6 p-6" >

    <div class="neo-card p-8 mb-8 relative overflow-hidden" style="background-color: #6C5CE7; color: #FFFFFF;">
        <div class="absolute inset-0 pattern-dot opacity-20 pointer-events-none"></div>
        
        <span class="absolute top-4 right-4 bg-[#FDCB6E] text-[#1E1B29] border-2 border-[#1E1B29] font-anime-header text-xs px-3 py-1.5 rounded-lg rotate-3 shadow-[3px_3px_0px_rgba(30,27,41,1)]">
            ( ≧◡≦ ) HELLO!
        </span>

        <div class="relative z-10">
            <span class="font-anime-header text-xs uppercase tracking-widest bg-[#00CEC9] text-[#1E1B29] border-2 border-[#1E1B29] px-3 py-1 rounded-full shadow-[2px_2px_0px_#1E1B29] inline-block">
                Dashboard Utama
            </span>
            <h1 class="font-anime-header text-4xl lg:text-5xl mt-4 mb-3 tracking-wide" style="text-shadow: 3px 3px 0px #1E1B29; -webkit-text-stroke: 1.5px #1E1B29; line-height: 1.2;">
                OVERVIEW
            </h1>
            <p class="font-anime-body text-base font-semibold max-w-xl text-white opacity-95 mt-2">
                Ringkasan ekosistem Scoola hari ini. Pantau kehadiran siswa dan efektivitas pengajaran secara real-time dengan energi penuh! ⚡
            </p>
        </div>
    </div>

    @php 
        $stats = [
            ['Total Siswa', $totalSiswa, "$totalKelasAktif unit kelas aktif", '#00CEC9', '🎓'], 
            ['Kehadiran', $persentaseHadir . '%', "$hadirHariIni siswa hadir", '#FF7675', '✨'], 
            ['Izin / Sakit', $izinSakitHariIni, "siswa berhalangan", '#FFFFFF', '💬'], 
            ['Absensi Alpha', $alpaHariIni, $alpaHariIni > 0 ? 'Perlu tindakan segera' : 'Data aman', '#FDCB6E', '⚠️']
        ];
    @endphp

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        @foreach($stats as $index => $s)
            <div class="neo-card p-6 flex flex-col justify-between" style="background-color: {{ $s[3] }}; min-height: 180px;">
                <div>
                    <div class="flex justify-between items-center mb-3">
                        <span class="font-anime-header text-xs uppercase tracking-wider text-[#1E1B29] opacity-80">{{ $s[0] }}</span>
                        <span class="text-xl">{{ $s[4] }}</span>
                    </div>
                    <div class="font-anime-header text-4xl text-[#1E1B29]" style="text-shadow: 2px 2px 0px #FFFFFF, -1px -1px 0px #1E1B29; -webkit-text-stroke: 1px #1E1B29; line-height: 1.1;">
                        {{ $s[1] }}
                    </div>
                </div>
                <div class="mt-4 pt-3 border-t-2 border-dashed border-[#1E1B29]">
                    <span class="text-xs font-black uppercase tracking-wide text-[#1E1B29]">
                        {{ $s[2] }}
                    </span>
                </div>
            </div>
        @endforeach
    </div>

    <div class="neo-layout">
        
        <div class="flex flex-col gap-8">
            <div>
                <div class="flex justify-between items-center mb-6 flex-wrap gap-4">
                    <h2 class="font-anime-header text-xl text-[#1E1B29] flex items-center gap-2">
                        <span>📝</span> Absensi Masuk Terbaru
                    </h2>
                    <a href="/admin/absensi" class="neo-btn bg-[#00CEC9] text-[#1E1B29] text-xs px-4 py-2 rounded-lg no-underline inline-flex items-center">
                        Lihat Semua →
                    </a>
                </div>
                
                <div class="overflow-x-auto border-[3px] border-[#1E1B29] rounded-xl">
                    <table class="neo-table">
                        <thead>
                            <tr>
                                <th>Siswa</th>
                                <th>Kelas</th>
                                <th>Waktu</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($absensiTerbaru as $absen)
                                <tr>
                                    <td class="font-bold text-[#1E1B29]">{{ $absen->siswa->nama_siswa ?? '-' }}</td>
                                    <td>
                                        <span class="bg-[#FAF9FF] border-2 border-[#1E1B29] px-2.5 py-1 rounded-md text-xs font-extrabold inline-block">
                                            {{ $absen->siswa->kelas ?? '-' }}
                                        </span>
                                    </td>
                                    <td class="font-mono text-xs">{{ $absen->jam_masuk ?? '—' }}</td>
                                    <td>
                                        <span class="badge-anime {{ $absen->status == 'Hadir' ? 'badge-anime-hadir' : 'badge-anime-absen' }}">
                                            {{ $absen->status }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-12 text-gray-500 font-bold italic bg-[#FAF9FF]">
                                        (′·_·`) Belum ada aktivitas terekam hari ini
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="neo-card p-6 bg-white">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="font-anime-header text-xl text-[#1E1B29] flex items-center gap-2">
                        <span>⚡</span> Agenda Belajar Hari Ini
                    </h2>
                    <a href="/admin/jadwal" class="neo-btn bg-[#FF7675] text-white text-xs px-4 py-2 rounded-lg no-underline inline-flex items-center">
                        Kelola
                    </a>
                </div>
                
                <div class="flex flex-col gap-4">
                    @foreach([['07:00','Matematika','X-A','Pak Hendra', '#00CEC9'],['08:30','B. Indonesia','XI-B','Bu Dewi', '#FDCB6E'],['10:15','Fisika','XII-C','Pak Rizal', '#FF7675']] as $j)
                        <div class="neo-card p-4 flex flex-col sm:flex-row justify-between sm:items-center gap-4 hover:scale-[1.01]" style="background-color: #FAF9FF; border-left: 8px solid {{ $j[4] }} !important;">
                            <div class="flex items-center gap-4">
                                <div class="font-anime-header bg-[#1E1B29] text-white py-1 px-3 rounded-lg text-sm shadow-[2px_2px_0px_#6C5CE7]">
                                    {{ $j[0] }}
                                </div>
                                <div>
                                    <h4 class="font-anime-header text-lg text-[#1E1B29] leading-tight mb-1">{{ $j[1] }}</h4>
                                    <span class="bg-[#1E1B29] text-white text-[10px] font-bold uppercase px-2 py-0.5 rounded inline-block">
                                        Kelas {{ $j[2] }}
                                    </span>
                                </div>
                            </div>
                            <div class="font-anime-header text-sm text-[#6C5CE7] flex items-center gap-1">
                                <span>👤</span> {{ $j[3] }}
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

        </div>

        <div class="flex flex-col gap-8">
            
            <div class="neo-card p-6 bg-white">
                <h2 class="font-anime-header text-xl text-[#1E1B29] mb-6 flex items-center gap-2">
                    <span>📊</span> Analitik Kehadiran
                </h2>
                
                <div class="h-48 flex items-end gap-3 border-b-4 border-[#1E1B29] pb-4 px-2 mb-4 bg-[#FAF9FF] rounded-xl border-t border-l border-r border-gray-100">
                    @foreach($trendData as $trend)
                        <div class="flex-1 flex flex-col justify-end items-center h-full group relative">
                            <span class="absolute -top-8 bg-[#1E1B29] text-white text-[10px] font-anime-header px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none whitespace-nowrap z-30">
                                {{ $trend->is_empty ? '0' : $trend->pct_hadir }}%
                            </span>
                            
                            @php
                                $trendHeight = $trend->is_empty ? '10px' : ($trend->pct_hadir . '%');
                                $trendBg = $trend->is_empty ? '#FAF9FF' : ($loop->iteration % 2 == 0 ? '#00CEC9' : '#6C5CE7');
                            @endphp
                            <div class="w-full rounded-t-md transition-all duration-300 border-2 border-[#1E1B29]"
                                 style="height: {{ $trendHeight }};
                                        background-color: {{ $trendBg }};
                                        box-shadow: 2px 0px 0px 0px #1E1B29;">
                            </div>
                        </div>
                    @endforeach
                </div>
                
                <div class="flex justify-between px-1">
                    @foreach($trendData as $trend)
                        <div class="font-anime-header text-xs text-[#1E1B29] uppercase tracking-wider text-center w-full">
                            {{ substr($trend->hari, 0, 3) }}
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="neo-card p-6 bg-white">
                <h2 class="font-anime-header text-xl text-[#1E1B29] mb-6 flex items-center gap-2">
                    <span>🏫</span> Data Per Kelas
                </h2>
                
                <div class="flex flex-col gap-4">
                    @forelse($kelasBreakdown as $kb)
                        <div>
                            <div class="flex justify-between items-center mb-1.5 font-bold text-sm">
                                <span class="font-anime-header text-[#1E1B29]">{{ $kb->nama }}</span>
                                <span class="bg-[#FDCB6E] border-2 border-[#1E1B29] px-2 py-0.5 text-xs rounded font-extrabold shadow-[1.5px_1.5px_0px_#1E1B29]">
                                    {{ $kb->persentase }}%
                                </span>
                            </div>
                            <div class="w-full h-4 bg-[#FAF9FF] border-2 border-[#1E1B29] rounded-full overflow-hidden">
                                <div class="h-full border-r-2 border-[#1E1B29]" 
                                     style="width: {{ $kb->persentase }}%; background-color: #6C5CE7;">
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center text-gray-400 text-sm font-bold py-6 italic">(′·_·`) Menunggu data...</div>
                    @endforelse
                </div>
            </div>

            <div class="neo-card p-6 bg-[#FF7675] text-[#1E1B29] relative overflow-hidden">
                <div class="absolute inset-0 pattern-dot opacity-10 pointer-events-none"></div>
                
                <h2 class="font-anime-header text-xl text-white mb-6 flex items-center gap-2" style="text-shadow: 2px 2px 0px #1E1B29; -webkit-text-stroke: 1px #1E1B29;">
                    <span>🚨</span> Laporan Kritikal
                </h2>
                
                <div class="flex flex-col gap-4 relative z-10">
                    <div class="bg-white p-4 border-2 border-[#1E1B29] rounded-xl shadow-[3px_3px_0px_0px_#1E1B29]">
                        <h4 class="font-anime-header text-base text-[#1E1B29] mb-1">Anomali Kehadiran</h4>
                        <p class="text-xs text-gray-700 font-semibold leading-relaxed">
                            Terdapat 5 siswa dengan status Alpha berulang minggu ini.
                        </p>
                        <a href="#" class="inline-block mt-3 font-anime-header text-xs text-[#6C5CE7] no-underline hover:underline">
                            Tindak Lanjut →
                        </a>
                    </div>
                    
                    <div class="bg-white p-4 border-2 border-[#1E1B29] rounded-xl shadow-[3px_3px_0px_0px_#1E1B29]">
                        <h4 class="font-anime-header text-base text-[#1E1B29] mb-1">Sesi Belum Lengkap</h4>
                        <p class="text-xs text-gray-700 font-semibold leading-relaxed">
                            Kelas XII-A belum mengunggah rekap presensi jam ke-4.
                        </p>
                    </div>
                </div>
            </div>

        </div>

    </div>
</div>

@endsection
