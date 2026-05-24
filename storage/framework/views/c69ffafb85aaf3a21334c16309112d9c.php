<?php $__env->startSection('content'); ?>

<div style="display: flex; flex-direction: column; gap: var(--spacing-md);">
    
    <!-- Header Card -->
    <div class="card" style="background: #ffffff; padding: 40px; border-radius: 12px; border: 1px solid var(--color-hairline);">
        <div class="editorial-header" style="margin: 0;">
            <span class="eyebrow" style="color: var(--color-stone); text-transform: uppercase; letter-spacing: 0.1em; font-size: 11px; font-weight: 700;">Manajemen Institusi</span>
            <h1 class="display-title" style="font-size: 48px; font-weight: 400; letter-spacing: var(--tracking-tighter); margin: 8px 0 24px 0; text-transform: uppercase;">Wali Kelas</h1>
            <p class="text-body" style="color: var(--color-graphite); max-width: 600px; font-size: 16px; line-height: 1.5; margin: 0;">
                Kelola penugasan guru sebagai wali kelas untuk setiap rombongan belajar aktif di Scoola.
            </p>
        </div>
    </div>


    <!-- Stats Grid -->
    <?php
        $totalKelas = $kelasList->count();
        $terisi = $kelasList->whereNotNull('wali_kelas_nip')->count();
    ?>
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(340px, 1fr)); gap: var(--spacing-md);">
        <!-- Standardized Premium Design: Total Kelas -->
        <div class="card" style="background: #ffffff; padding: 40px; border-radius: 12px; border: 1px solid var(--color-hairline); position: relative; overflow: hidden;">
            <div style="position: absolute; left: 16px; top: 40px; bottom: 40px; width: 4px; background: var(--color-ink); border-radius: 2px;"></div>
            <div style="position: absolute; right: 16px; top: 40px; bottom: 40px; width: 4px; background: var(--color-ink); border-radius: 2px;"></div>
            <div style="position: absolute; top: 24px; right: 32px; width: 48px; height: 48px; background: #ffffff; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: var(--color-ink); border: 1px solid var(--color-hairline);">
                <i class="bi bi-door-open" style="font-size: 20px;"></i>
            </div>
            <div style="padding-left: 12px;">
                <div class="text-micro-caps" style="color: var(--color-stone); margin-bottom: 12px; font-weight: 800; letter-spacing: 0.25em; font-size: 10px;">TOTAL UNIT KELAS</div>
                <div style="font-size: 56px; color: var(--color-ink); font-weight: 400; letter-spacing: var(--tracking-tighter); line-height: 1;"><?php echo e($totalKelas); ?></div>
            </div>
        </div>

        <!-- Standardized Premium Design: Wali Aktif -->
        <div class="card" style="background: #ffffff; padding: 40px; border-radius: 12px; border: 1px solid var(--color-hairline); position: relative; overflow: hidden;">
            <div style="position: absolute; left: 16px; top: 40px; bottom: 40px; width: 4px; background: var(--color-ink); border-radius: 2px;"></div>
            <div style="position: absolute; right: 16px; top: 40px; bottom: 40px; width: 4px; background: var(--color-ink); border-radius: 2px;"></div>
            <div style="position: absolute; top: 24px; right: 32px; width: 48px; height: 48px; background: #ffffff; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: var(--color-ink); border: 1px solid var(--color-hairline);">
                <i class="bi bi-person-check" style="font-size: 20px;"></i>
            </div>
            <div style="padding-left: 12px;">
                <div class="text-micro-caps" style="color: var(--color-stone); margin-bottom: 12px; font-weight: 800; letter-spacing: 0.25em; font-size: 10px;">WALI KELAS AKTIF</div>
                <div style="font-size: 56px; color: var(--color-ink); font-weight: 400; letter-spacing: var(--tracking-tighter); line-height: 1;"><?php echo e($terisi); ?></div>
            </div>
        </div>
    </div>


    <!-- Search Card -->
    <div class="card" style="background: #ffffff; padding: 32px; border-radius: 12px; border: 1px solid var(--color-hairline);">
        <label class="text-micro-caps" style="color: var(--color-stone); display: block; margin-bottom: 12px; font-weight: 700;">Pencarian Direktori</label>
        <input type="text" id="searchInput" placeholder="CARI KELAS ATAU GURU..." style="width: 100%; border: none; outline: none; background: transparent; font-family: var(--font-family-base); font-size: 18px; text-transform: uppercase; letter-spacing: 1px; color: var(--color-ink); font-weight: 500;">
        <div style="height: 1px; background: var(--color-hairline); margin-top: 12px;"></div>
    </div>

    <!-- Table Card -->
    <!-- Table Card -->
    <div class="card table-container-card" style="margin-bottom: var(--spacing-section);">
        <table class="data-table responsive-table" style="margin: 0; width: 100%;" id="walikelasTable">
            <thead>
                <tr>
                    <th style="padding-left: 40px;">Kelas</th>
                    <th>Wali Kelas</th>
                    <th>NIP</th>
                    <th style="text-align: center;">Siswa</th>
                    <th style="text-align: right; padding-right: 40px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $kelasList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $kls): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr class="walikelas-row">
                    <td data-label="Kelas" style="padding-left: 40px; font-weight: 600;"><?php echo e($kls->nama_kelas); ?></td>
                    <td data-label="Wali Kelas">
                        <?php if($kls->waliKelas): ?>
                            <?php echo e($kls->waliKelas->nama_guru); ?>

                        <?php else: ?>
                            <span style="color: var(--color-stone); font-style: italic; font-size: 12px; letter-spacing: 0.5px;">TIDAK TERIDENTIFIKASI</span>
                        <?php endif; ?>
                    </td>
                    <td data-label="NIP" style="font-family: monospace; color: var(--color-stone);">
                        <?php echo e($kls->wali_kelas_nip ?? '—'); ?>

                    </td>
                    <td data-label="Siswa" style="text-align: center; color: var(--color-ink); font-weight: 600;">
                        <?php echo e($kls->siswa->count()); ?>

                    </td>
                    <td data-label="Aksi" style="text-align: right; padding-right: 40px;">
                        <?php if($kls->waliKelas): ?>
                            <a href="<?php echo e(route('admin.walikelas.edit', $kls->id)); ?>" class="btn-ghost" style="height: 32px; font-size: 11px; padding: 0 16px; text-decoration: none; display: inline-flex; align-items: center;">Edit</a>
                        <?php else: ?>
                            <a href="<?php echo e(route('admin.walikelas.create')); ?>?kelas_id=<?php echo e($kls->id); ?>" class="btn-primary" style="height: 32px; font-size: 11px; padding: 0 16px; text-decoration: none; display: inline-flex; align-items: center;">Assign</a>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="5" style="text-align: center; padding: 120px; color: var(--color-stone);">Belum ada data wali kelas ditemukan.</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

</div>

<script>
    document.getElementById('searchInput').addEventListener('keyup', function() {
        const searchTerm = this.value.toLowerCase();
        const rows = document.querySelectorAll('.walikelas-row');
        
        rows.forEach(row => {
            const text = row.innerText.toLowerCase();
            row.style.display = text.includes(searchTerm) ? '' : 'none';
        });
    });
</script>

    <!-- FAB Action -->
    <div class="fab-container">
        <a href="<?php echo e(route('admin.walikelas.create')); ?>" class="btn-fab" title="Tambah Wali Kelas">
            <i class="bi bi-plus-lg" style="font-size: 28px;"></i>
            <span class="fab-label">Tambah Wali Kelas Baru</span>
        </a>
    </div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\coding\laravel\Scoola\resources\views/admin/walikelas/walikelas-index.blade.php ENDPATH**/ ?>