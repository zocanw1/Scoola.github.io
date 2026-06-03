<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Models\Guru;
use App\Models\JadwalPelajaran;
use App\Models\Presensi;
use App\Models\SesiPresensi;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();
        $todayString = $today->toDateString();
        $weekStart = $today->copy()->subDays(6);
        $hariIni = $this->hariIndonesia($today);

        $totalSiswa = Siswa::count();
        $totalKelasAktif = Siswa::distinct('kelas')->count('kelas');
        $studentCountsByClass = Siswa::query()
            ->select('kelas', DB::raw('count(*) as total'))
            ->groupBy('kelas')
            ->pluck('total', 'kelas');
        $studentCompositionTotal = (int) (($studentCountsByClass['XI-SIJA 1'] ?? 0) + ($studentCountsByClass['XI-SIJA 2'] ?? 0));
        $studentComposition = collect([
            ['label' => 'XI-SIJA 1', 'total' => (int) ($studentCountsByClass['XI-SIJA 1'] ?? 0), 'color' => '#6C5CE7'],
            ['label' => 'XI-SIJA 2', 'total' => (int) ($studentCountsByClass['XI-SIJA 2'] ?? 0), 'color' => '#00CEC9'],
        ])->map(function (array $item) use ($studentCompositionTotal) {
            return (object) [
                'label' => $item['label'],
                'total' => $item['total'],
                'color' => $item['color'],
                'percentage' => $studentCompositionTotal > 0
                    ? round(($item['total'] / $studentCompositionTotal) * 100, 1)
                    : 0,
            ];
        })->all();

        $sesiHariIni = SesiPresensi::query()
            ->whereDate('created_at', $todayString)
            ->get(['id', 'kelas']);
        $sesiIdsHariIni = $sesiHariIni->pluck('id');
        $sesiCountByKelas = $sesiHariIni->countBy('kelas');
        $kelasSesiHariIni = $sesiCountByKelas->keys()->values();
        $siswaHarusAbsen = $sesiCountByKelas
            ->map(fn (int $sessionCount, string $kelas): int => ((int) ($studentCountsByClass[$kelas] ?? 0)) * $sessionCount)
            ->sum();

        $statusCounts = Presensi::query()
            ->select('status', DB::raw('count(*) as total'))
            ->whereIn('sesi_id', $sesiIdsHariIni)
            ->whereIn('status', ['Hadir', 'Izin', 'Sakit'])
            ->groupBy('status')
            ->pluck('total', 'status');

        $hadirHariIni = (int) ($statusCounts['Hadir'] ?? 0);
        $izinSakitHariIni = (int) (($statusCounts['Izin'] ?? 0) + ($statusCounts['Sakit'] ?? 0));
        $alpaHariIni = max(0, $siswaHarusAbsen - ($hadirHariIni + $izinSakitHariIni));
        $persentaseHadir = $siswaHarusAbsen > 0 ? round(($hadirHariIni / $siswaHarusAbsen) * 100, 1) : 0;

        $absensiTerbaru = Presensi::with('siswa')
            ->whereIn('sesi_id', $sesiIdsHariIni)
            ->orderBy('created_at', 'desc')
            ->take(6)
            ->get();

        $hadirByKelas = Presensi::query()
            ->select('sesi_presensis.kelas', DB::raw('count(*) as total'))
            ->join('sesi_presensis', 'sesi_presensis.id', '=', 'presensi.sesi_id')
            ->whereIn('presensi.sesi_id', $sesiIdsHariIni)
            ->where('presensi.status', 'Hadir')
            ->groupBy('sesi_presensis.kelas')
            ->pluck('total', 'kelas');

        $kelasBreakdown = $sesiCountByKelas->map(function (int $sessionCount, string $kelas) use ($studentCountsByClass, $hadirByKelas) {
            $ts = ((int) ($studentCountsByClass[$kelas] ?? 0)) * $sessionCount;
            $hd = (int) ($hadirByKelas[$kelas] ?? 0);

            return (object) [
                'nama' => $kelas,
                'persentase' => $ts > 0 ? round(($hd / $ts) * 100) : 0,
            ];
        })->values()->all();

        $dateKeys = collect(range(6, 0))->map(fn ($i) => $today->copy()->subDays($i)->toDateString());
        $sesiPerHari = SesiPresensi::query()
            ->selectRaw('date(created_at) as tanggal, kelas, count(*) as total_sesi')
            ->whereBetween('created_at', [$today->copy()->subDays(6)->startOfDay(), $today->copy()->endOfDay()])
            ->groupByRaw('date(created_at), kelas')
            ->get()
            ->groupBy('tanggal');

        $presensiPerHari = Presensi::query()
            ->selectRaw("tanggal, sum(case when status = 'Hadir' then 1 else 0 end) as hadir")
            ->selectRaw("sum(case when status in ('Izin', 'Sakit') then 1 else 0 end) as izin_sakit")
            ->whereBetween('tanggal', [$today->copy()->subDays(6)->toDateString(), $todayString])
            ->groupBy('tanggal')
            ->get()
            ->keyBy('tanggal');

        $trendData = [];
        foreach ($dateKeys as $dateKey) {
            $date = Carbon::parse($dateKey);
            $kelasHariIni = collect($sesiPerHari->get($dateKey, []));
            $tSiswaDay = $kelasHariIni->sum(
                fn ($row): int => ((int) ($studentCountsByClass[$row->kelas] ?? 0)) * (int) $row->total_sesi
            );
            $dailyCounts = $presensiPerHari->get($dateKey);
            $hDay = (int) ($dailyCounts->hadir ?? 0);
            $izDay = (int) ($dailyCounts->izin_sakit ?? 0);
            $alpDay = max(0, $tSiswaDay - ($hDay + $izDay));
            $totalActivity = $hDay + $izDay + $alpDay;
            $pctH = $totalActivity > 0 ? ($hDay / $totalActivity) * 100 : 0;
            $pctI = $totalActivity > 0 ? ($izDay / $totalActivity) * 100 : 0;
            $pctA = $totalActivity > 0 ? ($alpDay / $totalActivity) * 100 : 0;
            $hariIndo = ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'];

            $trendData[] = (object) [
                'hari' => $date->isToday() ? 'Today' : $hariIndo[$date->dayOfWeek],
                'pct_hadir' => $pctH,
                'pct_izin' => $pctI,
                'pct_alpa' => $pctA,
                'is_empty' => $totalActivity == 0,
            ];
        }

        $agendaHariIni = JadwalPelajaran::query()
            ->with([
                'mapel:kd_mapel,nama_mapel',
                'guru:NIP,nama_guru',
            ])
            ->where('hari', $hariIni)
            ->orderBy('jam_mulai')
            ->orderBy('kelas')
            ->limit(6)
            ->get(['kd_jp', 'hari', 'jam_mulai', 'jam_selesai', 'kd_mapel', 'NIP', 'kelas']);

        $anomalyRows = Presensi::query()
            ->with('siswa:NIS,nama_siswa,kelas')
            ->select('NIS', DB::raw('count(*) as total_alpha'))
            ->where('status', 'Alpa')
            ->whereBetween('tanggal', [$weekStart->toDateString(), $todayString])
            ->groupBy('NIS')
            ->havingRaw('count(*) >= 2')
            ->orderByDesc('total_alpha')
            ->limit(3)
            ->get();

        $overdueSessions = SesiPresensi::query()
            ->with(['jadwal.mapel:kd_mapel,nama_mapel'])
            ->whereDate('created_at', $todayString)
            ->where('status', 'aktif')
            ->whereNotNull('waktu_berlaku')
            ->where('waktu_berlaku', '<', now())
            ->orderBy('waktu_berlaku')
            ->limit(3)
            ->get(['id', 'kelas', 'kd_jp', 'waktu_berlaku', 'status', 'created_at']);

        $criticalReports = collect();

        if ($anomalyRows->isNotEmpty()) {
            $criticalReports->push((object) [
                'title' => 'Anomali Kehadiran',
                'summary' => $anomalyRows->count() . ' siswa mencatat Alpha berulang minggu ini.',
                'items' => $anomalyRows->map(function (Presensi $row): string {
                    $nama = $row->siswa?->nama_siswa ?? $row->NIS;
                    $kelas = $row->siswa?->kelas ?? '-';

                    return "{$nama} ({$kelas}) • {$row->total_alpha} Alpha";
                })->values(),
                'action_url' => route('admin.presensi-siswa.index', [
                    'tanggal_mulai' => $weekStart->toDateString(),
                    'tanggal_akhir' => $todayString,
                ]),
                'action_label' => 'Tinjau Presensi',
            ]);
        }

        if ($overdueSessions->isNotEmpty()) {
            $criticalReports->push((object) [
                'title' => 'Sesi Aktif Melewati Batas Waktu',
                'summary' => $overdueSessions->count() . ' sesi masih aktif meski waktu presensinya sudah habis.',
                'items' => $overdueSessions->map(function (SesiPresensi $session): string {
                    $mapel = $session->jadwal?->mapel?->nama_mapel ?? $session->kd_jp ?? '-';
                    $expiredAt = optional($session->waktu_berlaku)?->format('H:i') ?? '-';

                    return "{$session->kelas} • {$mapel} • berakhir {$expiredAt}";
                })->values(),
                'action_url' => route('admin.rekap.index', [
                    'kelas' => $overdueSessions->first()?->kelas,
                    'tanggal' => $todayString,
                ]),
                'action_label' => 'Buka Rekap',
            ]);
        }

        return view('admin.dashboard', compact(
            'totalSiswa', 'totalKelasAktif',
            'hadirHariIni', 'izinSakitHariIni', 'alpaHariIni', 'persentaseHadir',
            'siswaHarusAbsen', 'absensiTerbaru', 'kelasBreakdown', 'trendData',
            'studentComposition', 'studentCompositionTotal', 'agendaHariIni', 'criticalReports'
        ));
    }

    private function hariIndonesia(Carbon $date): string
    {
        return [
            'Sunday' => 'Minggu',
            'Monday' => 'Senin',
            'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis',
            'Friday' => 'Jumat',
            'Saturday' => 'Sabtu',
        ][$date->format('l')];
    }
}
