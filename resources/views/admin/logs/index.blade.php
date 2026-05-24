@extends('layouts.admin')

@section('content')

<div class="mp-page">
    <div class="mp-hero-wrap">
        <span class="mp-sticker">AUDIT TRAIL</span>
        <section class="mp-hero">
            <div class="mp-hero-content">
                <span class="mp-kicker"><i class="bi bi-journal-text"></i> Keamanan & Audit</span>
                <h1 class="mp-title">Log Aktivitas Admin</h1>
                <p class="mp-description">
                    Riwayat pencatatan tindakan administratif yang dilakukan oleh Administrator dan Personel Sistem.
                </p>
            </div>
        </section>
    </div>

    <section class="mp-table-card">
        <div class="mp-table-wrap">
            <table class="mp-table">
                <thead>
                    <tr>
                        <th style="width: 80px;">#</th>
                        <th>Nama Pengguna</th>
                        <th>Aktivitas / Deskripsi</th>
                        <th>IP Address</th>
                        <th class="mp-right" style="width: 220px;">Waktu Kejadian</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($logs as $index => $log)
                    <tr>
                        <td>{{ ($logs->currentPage() - 1) * $logs->perPage() + $index + 1 }}</td>
                        <td>{{ $log->user_name }}</td>
                        <td style="max-width: 440px; word-break: break-word;">{{ $log->activity }}</td>
                        <td class="mp-mono">{{ $log->ip_address }}</td>
                        <td class="mp-right">{{ $log->created_at->format('d M Y, H:i:s') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="mp-center" style="padding: 72px;">Belum ada data log aktivitas yang tercatat.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($logs->hasPages())
        <div style="padding: 24px 30px; border-top: 4px solid var(--midnight); background: var(--mochi); display: flex; justify-content: flex-end;">
            {{ $logs->links() }}
        </div>
        @endif
    </section>
</div>

@endsection
