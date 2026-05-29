<?php

namespace App\Support;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

class ScoolaBreadcrumbs
{
    private const ROOTS = [
        'admin' => ['label' => 'Admin', 'route' => 'admin.dashboard'],
        'guru' => ['label' => 'Guru', 'route' => 'guru.dashboard'],
        'siswa' => ['label' => 'Siswa', 'route' => 'siswa.dashboard'],
    ];

    private const FEATURES = [
        'admin' => [
            'dashboard' => ['label' => 'Dashboard', 'route' => 'admin.dashboard', 'noun' => 'Dashboard'],
            'siswa' => ['label' => 'Siswa', 'route' => 'siswa.index', 'noun' => 'Siswa'],
            'guru' => ['label' => 'Guru', 'route' => 'guru.index', 'noun' => 'Guru'],
            'admin' => ['label' => 'Kelola Admin', 'route' => 'admin.akun.index', 'noun' => 'Admin'],
            'kakonsli' => ['label' => 'Akun Kakonsli', 'route' => 'admin.kakonsli.index', 'noun' => 'Kakonsli'],
            'logs' => ['label' => 'Log Aktivitas', 'route' => 'admin.logs.index', 'noun' => 'Log Aktivitas'],
            'kelas' => ['label' => 'Kelas', 'route' => 'admin.kelas.index', 'noun' => 'Kelas'],
            'walikelas' => ['label' => 'Wali Kelas', 'route' => 'admin.walikelas.index', 'noun' => 'Wali Kelas'],
            'mapel' => ['label' => 'Mata Pelajaran', 'route' => 'mapel.index', 'noun' => 'Mapel'],
            'jadwal' => ['label' => 'Jadwal Pelajaran', 'route' => 'jadwal.index', 'noun' => 'Jadwal'],
            'presensi-siswa' => ['label' => 'Presensi Siswa', 'route' => 'admin.presensi-siswa.index', 'noun' => 'Presensi Siswa'],
            'rekap-presensi' => ['label' => 'Rekap Presensi', 'route' => 'admin.rekap.index', 'noun' => 'Rekap Presensi'],
        ],
        'guru' => [
            'dashboard' => ['label' => 'Dashboard', 'route' => 'guru.dashboard', 'noun' => 'Dashboard'],
            'presensi' => ['label' => 'Presensi', 'route' => 'guru.presensi.index', 'noun' => 'Presensi'],
            'presensi-siswa' => ['label' => 'Presensi Siswa', 'route' => 'guru.presensi-siswa.index', 'noun' => 'Presensi Siswa'],
            'rekap-presensi' => ['label' => 'Rekap Presensi', 'route' => 'guru.rekap.index', 'noun' => 'Rekap Presensi'],
        ],
        'siswa' => [
            'dashboard' => ['label' => 'Dashboard', 'route' => 'siswa.dashboard', 'noun' => 'Dashboard'],
        ],
    ];

    public static function build(Request $request, array $viewData = []): array
    {
        $route = $request->route();

        if (! $route) {
            return [];
        }

        $segments = collect(explode('/', trim($request->path(), '/')))
            ->filter()
            ->values()
            ->all();

        $context = $segments[0] ?? self::contextFromRouteName($route->getName());

        if (! $context || ! isset(self::ROOTS[$context])) {
            return [];
        }

        $featureKey = $segments[1] ?? 'dashboard';
        $feature = self::resolveFeature($context, $featureKey);
        $action = self::resolveAction($route->getName(), $segments, $feature['noun'] ?? $feature['label']);
        $subject = self::resolveSubject($viewData, $route->parameters());
        $crumbs = [
            self::item(self::ROOTS[$context]['label'], self::routeUrl(self::ROOTS[$context]['route'])),
        ];

        if ($feature) {
            $isFeatureLanding = in_array($action['key'], ['dashboard', 'index'], true) && ! $subject;

            if ($isFeatureLanding) {
                $crumbs[] = self::item($feature['label']);
            } else {
                $crumbs[] = self::item($feature['label'], self::routeUrl($feature['route']));
            }
        }

        foreach (self::resolveCustomCrumbs($route->getName(), $route->parameters(), $feature) as $customCrumb) {
            $crumbs[] = self::item($customCrumb);
        }

        if (! in_array($route->getName(), ['jadwal.kelas'], true)) {
            if (! in_array($action['key'], ['dashboard', 'index'], true)) {
                $crumbs[] = self::item($action['label']);
            }

            if ($subject) {
                $crumbs[] = self::item($subject);
            }
        }

        return self::finalize($crumbs);
    }

    private static function contextFromRouteName(?string $routeName): ?string
    {
        if (! $routeName) {
            return null;
        }

        if (Str::startsWith($routeName, 'guru.')) {
            return 'guru';
        }

        if (Str::startsWith($routeName, 'siswa.')) {
            return 'admin';
        }

        if (Str::startsWith($routeName, 'admin.')) {
            return 'admin';
        }

        return null;
    }

    private static function resolveFeature(string $context, string $featureKey): array
    {
        if (isset(self::FEATURES[$context][$featureKey])) {
            return self::FEATURES[$context][$featureKey];
        }

        return [
            'label' => Str::headline(str_replace('-', ' ', $featureKey)),
            'route' => self::ROOTS[$context]['route'],
            'noun' => Str::headline(str_replace('-', ' ', $featureKey)),
        ];
    }

    private static function resolveAction(?string $routeName, array $segments, string $noun): array
    {
        $actionKey = $routeName ? Str::afterLast($routeName, '.') : (count($segments) > 2 ? $segments[2] : 'index');
        $actionKey = Str::of((string) $actionKey)->replace('-', '_')->value();

        return match ($actionKey) {
            'dashboard' => ['key' => 'dashboard', 'label' => 'Dashboard'],
            'index' => ['key' => 'index', 'label' => $noun],
            'create' => ['key' => 'create', 'label' => 'Tambah ' . $noun],
            'edit' => ['key' => 'edit', 'label' => 'Edit ' . $noun],
            'show' => ['key' => 'show', 'label' => 'Detail ' . $noun],
            'export' => ['key' => 'export', 'label' => 'Export ' . $noun],
            'kelas' => ['key' => 'kelas', 'label' => 'Jadwal Kelas'],
            'ruang' => ['key' => 'ruang', 'label' => 'Ruang Kelas'],
            'tampil' => ['key' => 'tampil', 'label' => 'Kode Presensi'],
            default => ['key' => $actionKey, 'label' => Str::headline(str_replace('_', ' ', $actionKey))],
        };
    }

    private static function resolveCustomCrumbs(?string $routeName, array $routeParameters, array $feature): array
    {
        if ($routeName === 'jadwal.kelas') {
            $crumbs = [];
            $kelasLabel = self::extractLabel($routeParameters['kelas'] ?? null);
            $hariLabel = self::extractLabel($routeParameters['hari'] ?? null);

            if ($kelasLabel) {
                $crumbs[] = 'Jadwal Kelas';
                $crumbs[] = $kelasLabel;
            }

            if ($hariLabel) {
                $crumbs[] = $hariLabel;
            }

            return $crumbs;
        }

        if ($routeName === 'guru.presensi.ruang') {
            return ['Ruang Kelas'];
        }

        if ($routeName === 'guru.presensi.tampil') {
            return ['Kode Presensi'];
        }

        return [];
    }

    private static function resolveSubject(array $viewData, array $routeParameters): ?string
    {
        foreach (['guru', 'siswa', 'admin', 'kakonsli', 'kelas', 'mapel', 'jadwal'] as $key) {
            $label = self::extractLabel($viewData[$key] ?? null);

            if ($label) {
                return $label;
            }
        }

        foreach ($routeParameters as $key => $value) {
            if ($key === 'hari') {
                continue;
            }

            $label = self::extractLabel($value);

            if ($label && ! is_numeric($label)) {
                return $label;
            }
        }

        return null;
    }

    private static function extractLabel(mixed $value): ?string
    {
        if (is_string($value)) {
            return trim($value) !== '' ? trim($value) : null;
        }

        if (is_numeric($value)) {
            return (string) $value;
        }

        if (! is_object($value)) {
            return null;
        }

        foreach (['nama_guru', 'nama_siswa', 'nama_kelas', 'nama_mapel', 'name', 'email', 'kd_jp', 'NIP', 'NIS'] as $property) {
            if (isset($value->{$property}) && trim((string) $value->{$property}) !== '') {
                return trim((string) $value->{$property});
            }
        }

        return null;
    }

    private static function item(string $label, ?string $url = null): array
    {
        return [
            'label' => $label,
            'url' => $url,
        ];
    }

    private static function routeUrl(string $routeName): ?string
    {
        return Route::has($routeName) ? route($routeName) : null;
    }

    private static function finalize(array $crumbs): array
    {
        $clean = [];

        foreach ($crumbs as $crumb) {
            if (! is_array($crumb) || empty($crumb['label'])) {
                continue;
            }

            $last = end($clean);

            if ($last && $last['label'] === $crumb['label'] && $last['url'] === $crumb['url']) {
                continue;
            }

            $clean[] = $crumb;
        }

        $lastIndex = array_key_last($clean);

        if ($lastIndex !== null) {
            $clean[$lastIndex]['current'] = true;
            $clean[$lastIndex]['url'] = null;
        }

        return $clean;
    }
}
