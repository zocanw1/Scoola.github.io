<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portofolio Tim | Scoola</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Fredoka+One&family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --midnight: #1E1B29;
            --sakura: #FF7675;
            --cyber: #00CEC9;
            --cosmo: #6C5CE7;
            --gold: #FDCB6E;
            --mochi: #FAF9FF;
        }

        body { 
            font-family: 'Nunito', sans-serif; 
            background-color: var(--mochi); 
            color: var(--midnight); 
            background-image: radial-gradient(var(--cosmo) 0.6px, transparent 0);
            background-size: 32px 32px;
            background-attachment: fixed;
            min-height: 100vh;

            /* Latar Belakang Komik Titik-Titik Pop (Mochi Cream + Radial Cosmo Violet Dots) */
            background-color: var(--mochi);
            background-image: radial-gradient(var(--cosmo) 1.5px, transparent 0);
            background-size: 32px 32px;
            background-attachment: fixed;
            
        }

        .fredoka { font-family: 'Fredoka One', cursive; }
        
        .neo-brutalism { 
            border: 4px solid var(--midnight); 
            box-shadow: 8px 8px 0px 0px var(--midnight); 
            background-color: white;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .info-snippet {
            border: 2px solid var(--midnight);
            box-shadow: 4px 4px 0px 0px var(--midnight);
            background-color: var(--mochi);
            font-family: monospace;
        }

        .sticker {
            position: absolute;
            top: -10px;
            right: -10px;
            padding: 4px 12px;
            border: 3px solid var(--midnight);
            font-weight: 800;
            font-size: 0.75rem;
            transform: rotate(6deg);
            box-shadow: 3px 3px 0px var(--midnight);
        }

        .img-container { 
            border: 4px solid var(--midnight); 
            box-shadow: 6px 6px 0px 0px var(--midnight); 
            overflow: hidden; 
            aspect-ratio: 1 / 1; 
        }

        /* Hover effect yang lebih stabil (anti blur) */
        .card-hover:hover { 
            transform: translate(-4px, -4px); 
            box-shadow: 12px 12px 0px 0px var(--midnight); 
        }

        .back-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 16px;
            background-color: var(--midnight);
            color: white;
            text-decoration: none;
            border: 4px solid var(--midnight);
            font-weight: bold;
            margin-bottom: 24px;
            box-shadow: 4px 4px 0px 0px var(--cosmo);
            transition: all 0.2s ease;
        }

        .back-btn:hover {
            transform: translate(-2px, -2px);
            box-shadow: 6px 6px 0px 0px var(--cosmo);
        }
    </style>
</head>
<body class="p-4 md:p-10">

    <main class="max-w-6xl mx-auto">
        <a href="{{ url('/') }}" class="back-btn">
            ← Kembali ke Beranda
        </a>

        <header class="text-center mb-16">
            <div class="inline-block px-6 py-2 bg-white border-4 border-black rotate-[-2deg] mb-4 shadow-[4px_4px_0px_#1E1B29]">
                <h1 class="fredoka text-5xl text-[#6C5CE7]">Tentang Kami (✿◡‿◡)</h1>
            </div>
            <p class="font-bold text-lg opacity-70">Siswa SIJA yang penuh semangat & ambisius!</p>
        </header>

        <div class="grid md:grid-cols-3 gap-10">
            
            @forelse($teamMembers as $index => $member)
                @php
                    // Array rotasi gambar agar selang-seling estetik seperti file aslinya
                    $rotations = ['rotate-[-2deg]', 'rotate-[2deg]', 'rotate-[-1deg]'];
                    $currentRotation = $rotations[$index % count($rotations)];

                    // Ambil data skill dari JSON
                    $skills = json_decode($member->skills, true) ?? [];
                @endphp

                <div class="relative p-6 neo-brutalism card-hover">
                    <span class="sticker text-[#1E1B29]" style="background-color: {{ $member->sticker_bg ?? '#FDCB6E' }};">
                        {{ strtoupper($member->role) }}
                    </span>

                    <div class="w-full img-container mb-6 {{ $currentRotation }}" style="background-color: {{ $member->img_bg ?? '#6C5CE7' }};">
                        @if($member->photo)
                            <img src="{{ asset('storage/team-photos/' . $member->photo) }}" 
                                 alt="{{ $member->name }}" 
                                 class="w-full h-full object-cover"
                                 onerror="this.src='https://via.placeholder.com/400/{{ str_replace('#', '', $member->img_bg ?? '6C5CE7') }}?text={{ urlencode($member->name) }}'">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-white font-bold text-xl p-4 text-center">
                                {{ $member->name }}
                            </div>
                        @endif
                    </div>

                    <h2 class="fredoka text-2xl mb-1">{{ $member->name }}</h2>
                    <p class="font-bold mb-4 uppercase text-sm tracking-widest" style="color: {{ $member->role_color ?? '#FF7675' }};">
                        {{ $member->kelas }}
                    </p>
                    <p class="text-sm opacity-80 mb-4 italic leading-relaxed">"{{ $member->description }}"</p>
                    
                    <div class="mb-4 text-xs text-gray-700 leading-relaxed text-justify border-b-2 border-dashed border-gray-300 pb-4">
                        <p><strong>Jobdesk:</strong> {{ $member->jobdesk }}</p>
                    </div>

                    <div class="info-snippet p-3 mb-4 text-xs space-y-1">
                        <p><strong>NIS:</strong> {{ $member->nis }}</p>
                        <p><strong>TTL:</strong> {{ $member->birthplace }}, {{ \Carbon\Carbon::parse($member->birthdate)->format('d M Y') }}</p>
                        <p><strong>Telp:</strong> {{ $member->phone }}</p>
                    </div>

                    <div class="flex gap-2 justify-center flex-wrap">
                        @foreach($skills as $skillIndex => $skill)
                            @php
                                // Badge pertama di-set hitam (bg-black), sisanya menggunakan warna aksen dinamis
                                $badgeBg = ($skillIndex === 0) ? '#000000' : ($member->img_bg ?? '#00CEC9');
                                $badgeText = ($skillIndex === 0) ? '#FFFFFF' : '#1E1B29';
                            @endphp
                            <span class="px-3 py-1 border-2 border-black font-bold text-xs" style="background-color: {{ $badgeBg }}; color: {{ $badgeText }};">
                                {{ strtoupper($skill) }}
                            </span>
                        @endforeach
                    </div>
                </div>
            @empty
                <div class="col-span-3 text-center py-12 px-6 bg-white neo-brutalism">
                    <p class="text-gray-500 font-bold">Belum ada anggota tim yang ditambahkan ٩(☉_☉)۶</p>
                </div>
            @endforelse

        </div>

        <footer class="mt-20">
            <div class="bg-white p-8 neo-brutalism text-center max-w-2xl mx-auto">
                <h3 class="fredoka text-3xl mb-4 text-[#6C5CE7]">Mari Berteman! ✨</h3>
                <p class="font-bold mb-8">Punya proyek seru? Klik tombol di bawah!</p>
                
                <div class="flex justify-center gap-4 flex-wrap">
                    <a href="mailto:contact@scoola.app" class="bg-[#00CEC9] px-8 py-3 border-4 border-black font-bold text-white hover:bg-[#009794] transition-all hover:translate-y-[-4px] shadow-[4px_4px_0px_#1E1B29]">Email Kami</a>
                    <a href="#" class="bg-[#FF7675] px-8 py-3 border-4 border-black font-bold text-white hover:bg-[#ff5e5d] transition-all hover:translate-y-[-4px] shadow-[4px_4px_0px_#1E1B29]">Instagram</a>
                </div>
            </div>
            <div class="text-center mt-8 font-bold opacity-50">
                Dibuat dengan semangat Anime ( ≧◡≦ ) &copy; 2026 Scoola Team
            </div>
        </footer>
    </main>

</body>
</html>