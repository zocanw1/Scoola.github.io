@extends('layouts.admin')

@section('content')

<div style="display: flex; flex-direction: column; gap: var(--spacing-md); max-width: 900px; margin: 0 auto;">

    <!-- Header Card -->
    <div class="card" style="background: #ffffff; padding: 32px; border-radius: 16px; border: 1px solid var(--color-hairline);">
        <div class="editorial-header" style="margin: 0;">
            <span class="eyebrow" style="color: var(--color-stone); text-transform: uppercase; letter-spacing: 0.15em; font-size: 11px; font-weight: 700;">Hak Akses Khusus</span>
            <h1 class="display-title" style="font-size: 44px; font-weight: 400; letter-spacing: var(--tracking-tighter); margin: 8px 0 16px 0; text-transform: uppercase;">Kepala Konseling (Kakonsli)</h1>
            <p class="text-body" style="color: var(--color-graphite); font-size: 15px; line-height: 1.6; margin: 0;">
                Sistem Scoola dirancang untuk memiliki <strong>hanya satu akun Kakonsli eksklusif</strong> yang bertugas memantau seluruh presensi dan bimbingan siswa secara terpusat.
            </p>
        </div>
    </div>

    @if(session('error'))
        <div style="border-left: 4px solid #e53e3e; background: #fff5f5; color: #c53030; padding: 16px; border-radius: 8px; font-size: 14px; font-weight: 500;">
            {{ session('error') }}
        </div>
    @endif

    @if(session('success'))
        <div style="border-left: 4px solid #38a169; background: #f0fff4; color: #276749; padding: 16px; border-radius: 8px; font-size: 14px; font-weight: 500;">
            {{ session('success') }}
        </div>
    @endif
    @if ($kakonsli)
        <!-- Exclusive Counselor Profile Card -->
        <div class="card" style="background: linear-gradient(135deg, #ffffff 0%, #f6f5f0 35%, #e8e5dd 70%, #ffffff 100%); color: var(--color-ink); padding: 48px; border-radius: 24px; border: 1px solid rgba(170, 124, 17, 0.2); position: relative; overflow: hidden; box-shadow: 0 20px 50px rgba(170, 124, 17, 0.06), 0 5px 15px rgba(0,0,0,0.03);">
            <!-- Decorative Gold Accent line -->
            <div style="position: absolute; top: 0; left: 0; right: 0; height: 4px; background: linear-gradient(90deg, #aa7c11, #d4af37, #f3e5ab, #d4af37, #aa7c11);"></div>
            
            <div style="position: absolute; right: -40px; bottom: -40px; font-size: 240px; color: rgba(170, 124, 17, 0.02); font-weight: 900; pointer-events: none; user-select: none;">
                COUNSELOR
            </div>

            <div style="display: flex; flex-direction: column; gap: 32px;">
                <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                    <div>
                        <span style="font-size: 11px; text-transform: uppercase; letter-spacing: 3px; color: #aa7c11; font-weight: 700;">Eksklusif / Satu-Satunya</span>
                        <div style="font-size: 14px; color: var(--color-stone); margin-top: 4px; font-weight: 500;">Akun Kepala Konseling Aktif</div>
                    </div>
                    <div style="width: 48px; height: 48px; border-radius: 50%; border: 1px solid #aa7c11; display: flex; align-items: center; justify-content: center; color: #aa7c11; background: rgba(170, 124, 17, 0.05);">
                        <i class="bi bi-shield-check" style="font-size: 22px;"></i>
                    </div>
                </div>

                <div style="display: flex; align-items: center; gap: 24px; z-index: 2;">
                    <!-- Stylized Avatar -->
                    <div style="width: 80px; height: 80px; border-radius: 50%; background: linear-gradient(135deg, #d4af37 0%, #aa7c11 100%); color: #ffffff; display: flex; align-items: center; justify-content: center; font-size: 32px; font-weight: 700; border: 4px solid #ffffff; box-shadow: 0 8px 24px rgba(170, 124, 17, 0.15);">
                        {{ strtoupper(substr($kakonsli->name, 0, 1)) }}
                    </div>

                    <div style="display: flex; flex-direction: column; gap: 6px;">
                        <h2 style="font-size: 28px; font-weight: 600; margin: 0; color: var(--color-ink); letter-spacing: -0.5px;">{{ $kakonsli->name }}</h2>
                        <span style="font-family: monospace; font-size: 14px; color: var(--color-slate); font-weight: 500;">{{ $kakonsli->email }}</span>
                    </div>
                </div>

                <div style="border-top: 1px solid var(--color-hairline); padding-top: 32px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px;">
                    <div style="font-size: 13px; color: var(--color-stone);">
                        Terdaftar sejak: <span style="color: var(--color-ink); font-weight: 600;">{{ $kakonsli->created_at->format('d M Y H:i') }}</span>
                    </div>
                    <div style="display: flex; gap: 12px; z-index: 2;">
                        <a href="{{ route('admin.kakonsli.edit', $kakonsli->id) }}" class="btn-primary" style="height: 44px; padding: 0 24px; font-size: 13px; font-weight: 600; border-radius: 8px; text-decoration: none; display: inline-flex; align-items: center; transition: all 0.2s;">
                            Edit Profil
                        </a>
                        <form action="{{ route('admin.kakonsli.destroy', $kakonsli->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun Kakonsli eksklusif ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" style="background: transparent; color: #e53e3e; border: 1px solid rgba(229, 62, 62, 0.2); height: 44px; padding: 0 24px; font-size: 13px; font-weight: 600; border-radius: 8px; cursor: pointer; display: inline-flex; align-items: center; transition: all 0.2s; gap: 8px;" onmouseover="this.style.background='rgba(229, 62, 62, 0.05)'" onmouseout="this.style.background='transparent'">
                                <i class="bi bi-trash"></i> Hapus Akun
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @else
        <!-- Placeholder Empty State with Action -->
        <div class="card" style="background: #ffffff; padding: 64px 32px; border-radius: 16px; border: 1px solid var(--color-hairline); text-align: center; display: flex; flex-direction: column; align-items: center; gap: 24px;">
            <div style="width: 80px; height: 80px; border-radius: 50%; background: var(--color-canvas); color: var(--color-stone); display: flex; align-items: center; justify-content: center; border: 1px solid var(--color-hairline);">
                <i class="bi bi-shield-slash" style="font-size: 36px;"></i>
            </div>
            <div>
                <h3 style="font-size: 20px; font-weight: 600; margin: 0 0 8px 0; color: var(--color-ink);">Kakonsli Belum Terdaftar</h3>
                <p style="color: var(--color-stone); max-width: 420px; font-size: 14px; line-height: 1.5; margin: 0;">
                    Saat ini belum ada akun Kepala Konseling (Kakonsli) yang terdaftar. Segera daftarkan satu akun untuk mengaktifkan akses bimbingan presensi siswa.
                </p>
            </div>
            <div>
                <a href="{{ route('admin.kakonsli.create') }}" class="btn-primary" style="height: 48px; padding: 0 32px; font-size: 14px; text-decoration: none; display: inline-flex; align-items: center; border-radius: 8px;">
                    Daftarkan Kakonsli
                </a>
            </div>
        </div>
    @endif

</div>

@endsection
