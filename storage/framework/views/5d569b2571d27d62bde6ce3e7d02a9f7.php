<?php $__env->startSection('content'); ?>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Fredoka+One&family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">

<style>
    /* Mengubah kontainer menjadi transparan agar background titik-titik dari layout induk terlihat penuh */
    .scoola-container {
        font-family: 'Nunito', sans-serif;
        color: #1E1B29;
        padding: 40px 20px;
        min-height: 100vh;
        background: transparent;
        display: flex;
        flex-direction: column;
        gap: 30px;
    }

    /* Karakter Utama Neobrutalism */
    .neo-card {
        background: #FFFFFF;
        border: 4px solid #1E1B29; /* --midnight */
        border-radius: 20px;
        box-shadow: 8px 8px 0px 0px #1E1B29;
        position: relative;
    }

    .neo-btn-primary {
        font-family: 'Fredoka One', cursive;
        text-transform: uppercase;
        letter-spacing: 1px;
        border: 3px solid #1E1B29;
        border-radius: 14px;
        cursor: pointer;
        background: #FF7675; /* --sakura */
        color: #FFFFFF;
        transition: all 0.2s ease;
        box-shadow: 5px 5px 0px #1E1B29;
        text-shadow: 2px 2px 0px #1E1B29;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
    }

    .neo-btn-primary:hover {
        transform: translate(-2px, -2px);
        box-shadow: 7px 7px 0px #1E1B29;
        background: #ff5e5d;
    }

    .neo-btn-primary:active {
        transform: translate(2px, 2px);
        box-shadow: 2px 2px 0px #1E1B29;
    }

    .fredoka {
        font-family: 'Fredoka One', cursive;
    }
</style>

<div class="scoola-container">
    <div style="max-width: 900px; margin: 0 auto; width: 100%; display: flex; flex-direction: column; gap: 30px;">
        
        <div class="neo-card" style="background: #6C5CE7; color: #FFFFFF; padding: 40px; overflow: hidden; background-image: linear-gradient(45deg, rgba(30, 27, 41, 0.05) 25%, transparent 25%), linear-gradient(-45deg, rgba(30, 27, 41, 0.05) 25%, transparent 25%); background-size: 30px 30px;">
            <div style="position: absolute; right: -20px; bottom: -20px; opacity: 0.15; font-size: 110px; font-weight: 900; font-family: 'Fredoka One', cursive; user-select: none; pointer-events: none;">
                KAWAII
            </div>
            
            <span style="display: inline-block; font-size: 11px; text-transform: uppercase; letter-spacing: 1.5px; color: #1E1B29; font-weight: 800; background: #FDCB6E; border: 2px solid #1E1B29; padding: 4px 12px; border-radius: 6px; margin-bottom: 12px; transform: rotate(-1deg);">
                ✨ HAK AKSES KHUSUS
            </span>
            <h1 class="fredoka" style="font-size: clamp(32px, 4vw, 48px); line-height: 1.1; margin: 8px 0 16px 0; color: #FFFFFF; text-shadow: 4px 4px 0px #1E1B29; -webkit-text-stroke: 1.5px #1E1B29;">
                Kepala Konsentrasi Ke Ahlian (Kakonsli)
            </h1>
            <p style="font-size: 16px; line-height: 1.6; font-weight: 600; background: rgba(30, 27, 41, 0.85); padding: 20px; border-radius: 12px; border: 3px solid #1E1B29; box-shadow: 5px 5px 0px 0px #1E1B29; margin: 0; color: #FFFFFF;">
                Sistem Scoola dirancang untuk memiliki <strong style="color: #FDCB6E;">hanya satu akun Kakonsli eksklusif</strong> yang bertugas memantau seluruh presensi dan bimbingan siswa secara terpusat. ⚡
            </p>
        </div>

        <?php if(session('error')): ?>
            <div class="neo-card" style="background: rgba(255, 118, 117, 0.15); padding: 16px 20px; font-weight: 800; font-size: 14px; border-color: #1E1B29;">
                ❌ <?php echo e(session('error')); ?>

            </div>
        <?php endif; ?>

        <?php if(session('success')): ?>
            <div class="neo-card" style="background: rgba(0, 206, 201, 0.15); padding: 16px 20px; font-weight: 800; font-size: 14px; border-color: #1E1B29;">
                ⚡ <?php echo e(session('success')); ?>

            </div>
        <?php endif; ?>

        <?php if($kakonsli): ?>
            <div class="neo-card" style="padding: 40px; background: #FFFFFF;">
                
                <span class="fredoka" style="position: absolute; top: -20px; right: 30px; background: #FF7675; color: #FFFFFF; border: 3px solid #1E1B29; padding: 4px 16px; font-size: 14px; border-radius: 8px; box-shadow: 3px 3px 0px #1E1B29; transform: rotate(4deg);">
                    ( ≧◡≦ ) ACTIVE
                </span>

                <div style="display: flex; flex-direction: column; gap: 28px;">
                    <div style="display: flex; justify-content: space-between; align-items: flex-start; border-bottom: 3px dashed #1E1B29; padding-bottom: 20px;">
                        <div>
                            <span class="fredoka" style="font-size: 12px; text-transform: uppercase; letter-spacing: 2px; color: #6C5CE7;">Eksklusif / Satu-Satunya</span>
                            <div class="fredoka" style="font-size: 22px; color: #1E1B29; margin-top: 4px;">Akun Kepala Konseling Aktif</div>
                        </div>
                        <div style="width: 54px; height: 54px; border-radius: 12px; border: 3px solid #1E1B29; display: flex; align-items: center; justify-content: center; color: #1E1B29; background: #00CEC9; box-shadow: 3px 3px 0px #1E1B29;">
                            <i class="bi bi-shield-check" style="font-size: 26px;"></i>
                        </div>
                    </div>

                    <div style="display: flex; align-items: center; gap: 24px; flex-wrap: wrap;">
                        <div class="fredoka" style="width: 90px; height: 90px; border-radius: 50%; background: linear-gradient(135deg, #FDCB6E 0%, #6C5CE7 100%); color: #FFFFFF; display: flex; align-items: center; justify-content: center; font-size: 38px; border: 4px solid #1E1B29; box-shadow: 5px 5px 0px #1E1B29; text-shadow: 2px 2px 0px #1E1B29;">
                            <?php echo e(strtoupper(substr($kakonsli->name, 0, 1))); ?>

                        </div>

                        <div style="display: flex; flex-direction: column; gap: 4px;">
                            <h2 class="fredoka" style="font-size: 32px; margin: 0; color: #1E1B29; text-shadow: 2px 2px 0px #00CEC9;">
                                <?php echo e($kakonsli->name); ?>

                            </h2>
                            <span style="font-family: monospace; font-size: 15px; color: #1E1B29; font-weight: 700; background: #FAF9FF; border: 2px solid #1E1B29; padding: 2px 8px; border-radius: 6px; display: inline-block; box-shadow: 2px 2px 0px #1E1B29;">
                                <?php echo e($kakonsli->email); ?>

                            </span>
                        </div>
                    </div>

                    <div style="border-top: 3px solid #1E1B29; padding-top: 24px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px;">
                        <div style="font-size: 14px; font-weight: 700; color: #1E1B29;">
                            Terdaftar sejak: <span style="color: #6C5CE7; background: rgba(108, 92, 231, 0.1); padding: 2px 6px; border-radius: 4px;"><?php echo e($kakonsli->created_at->format('d M Y H:i')); ?></span>
                        </div>
                        
                        <a href="<?php echo e(route('admin.kakonsli.edit', $kakonsli->id)); ?>" class="neo-btn-primary" style="height: 48px; padding: 0 28px; font-size: 14px;">
                            Edit Profil 🛠️
                        </a>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <div class="neo-card" style="background: #FFFFFF; padding: 60px 32px; text-align: center; display: flex; flex-direction: column; align-items: center; gap: 24px;">
                
                <div style="width: 90px; height: 90px; border-radius: 20px; background: #FAF9FF; color: #1E1B29; display: flex; align-items: center; justify-content: center; border: 3px solid #1E1B29; box-shadow: 5px 5px 0px #1E1B29; transform: rotate(-4deg);">
                    <i class="bi bi-shield-slash" style="font-size: 42px; color: #FF7675;"></i>
                </div>
                
                <div>
                    <h3 class="fredoka" style="font-size: 26px; margin: 0 0 10px 0; color: #1E1B29;">
                        Kakonsli Belum Terdaftar (′·_·`)
                    </h3>
                    <p style="color: #1E1B29; max-width: 460px; font-size: 15px; line-height: 1.6; font-weight: 600; margin: 0;">
                        Saat ini belum ada akun Kepala Counseling (Kakonsli) yang aktif. Segera daftarkan satu akun untuk mengaktifkan akses pusat bimbingan presensi siswa!
                    </p>
                </div>
                
                <div style="margin-top: 10px;">
                    <a href="<?php echo e(route('admin.kakonsli.create')); ?>" class="neo-btn-primary" style="height: 54px; padding: 0 36px; font-size: 15px; background: #00CEC9;">
                        Daftarkan Kakonsli ✨
                    </a>
                </div>
            </div>
        <?php endif; ?>

        <div class="fredoka" style="text-align: center; margin-top: 20px; opacity: 0.6; font-size: 14px; color: #1E1B29;">
            (✿◡‿◡) &bull; Presensi Jadi Lebih Menyenangkan!
        </div>

    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\coding\laravel\Scoola\resources\views/admin/kakonsli/index.blade.php ENDPATH**/ ?>