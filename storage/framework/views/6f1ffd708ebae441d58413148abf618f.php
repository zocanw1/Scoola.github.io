<?php $__env->startSection('content'); ?>

<div style="display: flex; flex-direction: column; gap: var(--spacing-md);">
    
    <!-- Header Card -->
    <div class="card" style="background: #ffffff; padding: 40px; border-radius: 12px; border: 1px solid var(--color-hairline);">
        <div class="editorial-header" style="margin: 0;">
            <span class="eyebrow" style="color: var(--color-stone); text-transform: uppercase; letter-spacing: 0.1em; font-size: 11px; font-weight: 700;">Struktur Institusi</span>
            <h1 class="display-title" style="font-size: 48px; font-weight: 400; letter-spacing: var(--tracking-tighter); margin: 8px 0 24px 0; text-transform: uppercase;">Data Kelas</h1>
            <p class="text-body" style="color: var(--color-graphite); max-width: 600px; font-size: 16px; line-height: 1.5; margin: 0;">
                Daftar seluruh rombongan belajar aktif. Pilih kelas untuk mengelola data siswa dan informasi spesifik lainnya.
            </p>
        </div>
    </div>

    <?php
        $countKelas = count($kelasList);
        $itemIndex = 0;
    ?>

    <!-- Stats Summary -->
    <div class="stats-grid">
        <div class="card" style="background: #ffffff; padding: 32px; border-radius: 16px; border: 1px solid var(--color-hairline); position: relative; overflow: hidden; min-height: 140px; display: flex; align-items: center;">
            <div style="position: absolute; left: 16px; top: 40px; bottom: 40px; width: 4px; background: var(--color-ink); border-radius: 2px;"></div>
            <div style="position: absolute; right: 16px; top: 40px; bottom: 40px; width: 4px; background: var(--color-ink); border-radius: 2px;"></div>
            <div style="position: absolute; top: 24px; right: 32px; width: 48px; height: 48px; background: #ffffff; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: var(--color-ink); border: 1px solid var(--color-hairline);">
                <i class="bi bi-layers" style="font-size: 20px;"></i>
            </div>
            <div style="padding-left: 12px;">
                <div class="text-micro-caps" style="color: var(--color-stone); margin-bottom: 12px; font-weight: 800; letter-spacing: 0.25em; font-size: 10px;">TOTAL UNIT KELAS TERSEDIA</div>
                <div style="font-size: 56px; color: var(--color-ink); font-weight: 400; letter-spacing: var(--tracking-tighter); line-height: 1;"><?php echo e($countKelas); ?> <span style="font-size: 24px; letter-spacing: 0;">Unit</span></div>
            </div>
        </div>
    </div>

    <!-- Grid of Classes -->
    <div class="responsive-card-grid" style="margin-bottom: var(--spacing-section);">
        <?php $__currentLoopData = $kelasList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $kelas): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php
                $count = $counts->get($kelas) ?? 0;
                $itemIndex++;
                $isFullWidth = ($countKelas % 2 !== 0 && $itemIndex === 1) || $countKelas === 1;
            ?>
            <a href="<?php echo e(route('admin.kelas.show', $kelas)); ?>" class="card selection-card" 
               style="background: #ffffff; text-decoration: none; padding: 48px; color: var(--color-ink); display: block; border-radius: 16px; border: 1px solid var(--color-hairline);
               <?php if($isFullWidth): ?> grid-column: 1 / -1; <?php endif; ?>">
                <div class="text-micro-caps" style="color: var(--color-stone); margin-bottom: 16px; font-weight: 700;">VOL. 0<?php echo e($loop->iteration); ?></div>
                <div class="display-text" style="font-size: 32px; font-weight: 400; margin-bottom: 24px; letter-spacing: var(--tracking-tighter); text-transform: uppercase;">Kelas <?php echo e($kelas); ?></div>
                <div class="text-meta" style="color: var(--color-slate); font-weight: 700; font-size: 11px; text-transform: uppercase; letter-spacing: 0.05em;"><?php echo e($count); ?> SISWA TERDAFTAR</div>
                <div style="margin-top: 32px; font-size: 11px; text-transform: uppercase; letter-spacing: 1px; font-weight: 700; color: var(--color-ink); display: flex; align-items: center; gap: 8px;">
                    Lihat Detail <span style="font-size: 16px;">&rarr;</span>
                </div>
            </a>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>

</div>

    <!-- FAB Action -->
    <div class="fab-container">
        <a href="<?php echo e(route('siswa.create')); ?>" class="btn-fab" title="Tambah Siswa">
            <i class="bi bi-plus-lg" style="font-size: 28px;"></i>
            <span class="fab-label">Tambah Siswa Baru</span>
        </a>
    </div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\coding\laravel\Scoola\resources\views/admin/kelas/kelas-index.blade.php ENDPATH**/ ?>