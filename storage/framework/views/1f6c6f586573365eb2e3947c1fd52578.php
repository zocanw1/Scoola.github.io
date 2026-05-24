<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scoola — Editorial Platform</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Fredoka+One&family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --sakura: #FF7675;
            --cyber: #00CEC9;
            --cosmo: #6C5CE7;
            --gold: #FDCB6E;
            --midnight: #1E1B29;
            --mochi: #FAF9FF;
            --white: #FFFFFF;
        }

        body {
            background-color: var(--mochi);
            font-family: 'Nunito', sans-serif;
            color: var(--midnight);
            background-image: radial-gradient(var(--cosmo) 1px, transparent 0);
            background-size: 24px 24px;

            /* Latar Belakang Komik Titik-Titik Pop (Mochi Cream + Radial Cosmo Violet Dots) */
            background-color: var(--mochi);
            background-image: radial-gradient(var(--cosmo) 1.5px, transparent 0);
            background-size: 32px 32px;
            background-attachment: fixed;
            

        }

        .fredoka { font-family: 'Fredoka One', cursive; }

        .neo-brutalism {
            border: 3px solid var(--midnight);
            box-shadow: 5px 5px 0px 0px var(--midnight);
            transition: all 0.2s ease;
        }

        .neo-brutalism:hover {
            transform: translate(-2px, -2px);
            box-shadow: 7px 7px 0px 0px var(--midnight);
        }

        .neo-brutalism:active {
            transform: translate(2px, 2px);
            box-shadow: 2px 2px 0px 0px var(--midnight);
        }
    </style>
</head>
<body class="min-h-screen">

    <nav class="flex items-center justify-between px-10 py-6 bg-white border-b-[4px] border-[var(--midnight)] sticky top-0 z-50">
        <a href="<?php echo e(url('/')); ?>" class="fredoka text-3xl text-[var(--cosmo)] tracking-tight">Scoola.</a>
        <div class="flex gap-4 items-center">
            <a href="<?php echo e(route('portfolio')); ?>" class="font-bold uppercase tracking-wider text-sm hover:text-[var(--sakura)] hidden sm:inline-block">Tim Kami</a>
            <a href="#fitur" class="font-bold uppercase tracking-wider text-sm hover:text-[var(--sakura)] hidden sm:inline-block">Fitur</a>
            <a href="#faq" class="font-bold uppercase tracking-wider text-sm hover:text-[var(--sakura)] hidden sm:inline-block mr-4">FAQ</a>
            <a href="<?php echo e(route('login')); ?>" class="neo-brutalism px-5 py-2 bg-[var(--gold)] font-bold uppercase text-sm">Masuk ✨</a>
        </div>
    </nav>

    <section class="max-w-6xl mx-auto px-10 pt-20 pb-16">
        <div class="bg-[var(--cosmo)] text-white p-12 neo-brutalism border-[4px] relative">
            <span class="absolute -top-4 -right-2 bg-[var(--gold)] text-[var(--midnight)] border-[3px] border-[var(--midnight)] font-bold text-xs px-3 py-1 rounded-lg rotate-6 shadow-[3px_3px_0px_rgba(30,27,41,1)] fredoka">
                BARU ✨
            </span>
            <span class="inline-block bg-[var(--cyber)] text-[var(--midnight)] font-bold px-4 py-1 mb-4 border-[3px] border-[var(--midnight)] rotate-[-2deg]">
                REVOLUSI AKADEMIK ⚡
            </span>
            <h1 class="fredoka text-6xl md:text-8xl leading-tight mb-6" style="-webkit-text-stroke: 2px var(--midnight);">ACADEMIC<br>MANAGEMENT.</h1>
            <p class="text-xl font-semibold max-w-lg bg-[rgba(30,27,41,0.5)] p-6 border-[3px] border-[var(--midnight)] mb-8">
                Platform kehadiran modern yang menggabungkan keseriusan profesional dan sentuhan visual anime yang ceria! (✿◡‿◡)
            </p>
            <a href="<?php echo e(route('login')); ?>" class="inline-block bg-[var(--sakura)] text-white font-bold px-8 py-4 text-lg neo-brutalism uppercase tracking-wider">
                Akses Sistem Sekarang! 🚀
            </a>
        </div>
    </section>

    <section id="fitur" class="max-w-6xl mx-auto px-10 pb-16">
        <div class="text-center mb-12">
            <h2 class="fredoka text-4xl text-[var(--midnight)] inline-block bg-[var(--gold)] px-6 py-2 border-[3px] border-[var(--midnight)] shadow-[4px_4px_0px_var(--midnight)] rotate-[-1deg]">
                MENGAPA MEMILIH SCOOLA? 🤔
            </h2>
        </div>
        <div class="grid md:grid-cols-3 gap-8">
            <div class="bg-white p-8 neo-brutalism border-[4px]">
                <div class="text-[var(--sakura)] font-bold mb-4 fredoka text-lg">01 / AESTHETICS</div>
                <h3 class="fredoka text-2xl mb-3">Editorial Design</h3>
                <p class="text-[var(--midnight)] font-semibold leading-relaxed">Tampilan bersih dengan fondasi struktur Swiss Design yang dibalut palet warna manga kontras tinggi. Bebas distrasi, fokus penuh pada data.</p>
            </div>
            <div class="bg-white p-8 neo-brutalism border-[4px]">
                <div class="text-[var(--cyber)] font-bold mb-4 fredoka text-lg">02 / FUNCTION</div>
                <h3 class="fredoka text-2xl mb-3">Real-time Presence</h3>
                <p class="text-[var(--midnight)] font-semibold leading-relaxed">Pencatatan presensi berbasis validasi kode unik dan koordinat GPS. Berjalan instan di seluruh perangkat ponsel maupun laptop tanpa alat tambahan.</p>
            </div>
            <div class="bg-white p-8 neo-brutalism border-[4px]">
                <div class="text-[var(--cosmo)] font-bold mb-4 fredoka text-lg">03 / CONTROL</div>
                <h3 class="fredoka text-2xl mb-3">Absolute Control</h3>
                <p class="text-[var(--midnight)] font-semibold leading-relaxed">Panel kendali terpusat untuk memantau absensi kelas, jadwal harian, rekap data siswa, serta manajemen akun guru dengan tingkat presisi operasional yang tinggi.</p>
            </div>
        </div>
    </section>

    <section id="faq" class="max-w-4xl mx-auto px-10 pb-20">
        <div class="text-center mb-12">
            <h2 class="fredoka text-4xl text-[var(--white)] inline-block bg-[var(--midnight)] px-6 py-2 border-[3px] border-[var(--midnight)] shadow-[4px_4px_0px_var(--sakura)] rotate-[1deg]">
                PERTANYAAN UMUM (FAQ) 💬
            </h2>
        </div>
        <div class="space-y-6">
            <div class="bg-white p-6 neo-brutalism border-[3px]">
                <h4 class="fredoka text-xl mb-2 text-[var(--cosmo)]">Apakah platform ini membutuhkan hardware khusus?</h4>
                <p class="font-semibold text-gray-700">Tidak sama sekali. Scoola berbasis web (cloud platform) yang dioptimasi penuh, sehingga guru dan siswa cukup membukanya langsung via browser bawaan smartphone atau laptop masing-masing.</p>
            </div>
            <div class="bg-white p-6 neo-brutalism border-[3px]">
                <h4 class="fredoka text-xl mb-2 text-[var(--sakura)]">Bagaimana sistem mendeteksi kecurangan absensi?</h4>
                <p class="font-semibold text-gray-700">Setiap sesi absensi diamankan dengan pencocokan geolokasi radius GPS koordinat sekolah dan token kode pendaftaran yang berubah dinamis dalam periode waktu tertentu.</p>
            </div>
            <div class="bg-white p-6 neo-brutalism border-[3px]">
                <h4 class="fredoka text-xl mb-2 text-[var(--cyber)]">Apakah data laporan kehadiran bisa diunduh?</h4>
                <p class="font-semibold text-gray-700">Tentu saja. Pihak administrasi sekolah atau bapak/ibu guru dapat melakukan ekspor seluruh rekap data presensi bulanan secara instan dalam format spreadsheet terstruktur.</p>
            </div>
        </div>
    </section>

    <section class="max-w-6xl mx-auto px-10 pb-24 text-center">
        <div class="bg-[var(--gold)] p-12 neo-brutalism border-[4px] flex flex-col items-center justify-center">
            <h2 class="fredoka text-4xl md:text-5xl mb-4 text-[var(--midnight)]">SIAP MEMULAI PENGALAMAN BARU?</h2>
            <p class="text-lg font-bold max-w-xl text-gray-800 mb-8 leading-relaxed">
                Tinggalkan metode pencatatan manual yang rentan hilang dan melelahkan. Daftarkan lembaga pendidikan Anda sekarang juga!
            </p>
            <div class="flex gap-4 flex-wrap justify-center">
                <a href="<?php echo e(route('login')); ?>" class="inline-block bg-[var(--midnight)] text-white font-bold px-8 py-4 text-base neo-brutalism uppercase tracking-wider">
                    Coba Gratis 🌟
                </a>
                <a href="<?php echo e(route('portfolio')); ?>" class="inline-block bg-white text-[var(--midnight)] font-bold px-8 py-4 text-base neo-brutalism uppercase tracking-wider">
                    Lihat Tim Kami
                </a>
            </div>
        </div>
    </section>

    <footer class="max-w-6xl mx-auto px-10 py-10 border-t-[3px] border-[var(--midnight)] flex flex-col md:flex-row items-center justify-between gap-4">
        <div class="font-bold opacity-70 text-sm">
            © 2026 Scoola Academic Systems. Seluruh Hak Cipta Dilindungi.
        </div>
        <div class="flex gap-6 font-bold text-sm">
            <a href="<?php echo e(route('portfolio')); ?>" class="hover:text-[var(--cosmo)] underline">Tim Kami</a>
            <a href="#" class="hover:text-[var(--cosmo)] underline">Kebijakan Privasi</a>
            <a href="#" class="hover:text-[var(--cosmo)] underline">Ketentuan Layanan</a>
            <a href="#" class="hover:text-[var(--cosmo)] underline">Hubungi Kami</a>
        </div>
    </footer>

</body>
</html><?php /**PATH C:\coding\laravel\Scoola\resources\views/welcome.blade.php ENDPATH**/ ?>