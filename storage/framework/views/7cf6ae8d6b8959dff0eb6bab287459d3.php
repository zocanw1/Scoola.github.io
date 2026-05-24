<?php $__env->startSection('content'); ?>

<div style="display: flex; flex-direction: column; gap: var(--spacing-md);">

    <!-- Header Card -->
    <div class="card" style="background: #ffffff; padding: 32px; border-radius: 16px; border: 1px solid var(--color-hairline);">
        <div class="editorial-header" style="margin: 0;">
            <span class="eyebrow" style="color: var(--color-stone); text-transform: uppercase; letter-spacing: 0.1em; font-size: 11px; font-weight: 700;">Keamanan & Audit</span>
            <h1 class="display-title" style="font-size: 48px; font-weight: 400; letter-spacing: var(--tracking-tighter); margin: 8px 0 24px 0; text-transform: uppercase;">Log Aktivitas Admin</h1>
            <p class="text-body" style="color: var(--color-graphite); max-width: 600px; font-size: 16px; line-height: 1.5; margin: 0;">
                Riwayat pencatatan tindakan administratif yang dilakukan oleh Administrator dan Personel Sistem.
            </p>
        </div>
    </div>

    <!-- Table Card -->
    <div class="card table-container-card" style="margin-bottom: var(--spacing-section); background: #ffffff; border-radius: 16px; border: 1px solid var(--color-hairline); overflow: hidden;">
        <table class="data-table responsive-table" style="margin: 0; width: 100%;">
            <thead>
                <tr>
                    <th style="padding-left: 40px; width: 80px;">#</th>
                    <th>Nama Pengguna</th>
                    <th>Aktivitas / Deskripsi</th>
                    <th>IP Address</th>
                    <th style="text-align: right; padding-right: 40px; width: 220px;">Waktu Kejadian</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $logs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td data-label="#" style="padding-left: 40px; color: var(--color-stone);">
                        <?php echo e(($logs->currentPage() - 1) * $logs->perPage() + $index + 1); ?>

                    </td>
                    <td data-label="Nama Pengguna" style="font-weight: 600; color: var(--color-ink);">
                        <?php echo e($log->user_name); ?>

                    </td>
                    <td data-label="Aktivitas" style="color: var(--color-graphite); max-width: 400px; word-wrap: break-word;">
                        <?php echo e($log->activity); ?>

                    </td>
                    <td data-label="IP Address" style="font-family: monospace; color: var(--color-stone); font-size: 13px;">
                        <?php echo e($log->ip_address); ?>

                    </td>
                    <td data-label="Waktu" style="text-align: right; padding-right: 40px; color: var(--color-stone);">
                        <?php echo e($log->created_at->format('d M Y, H:i:s')); ?>

                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="5" style="text-align: center; padding: 40px; color: var(--color-stone);">
                        Belum ada data log aktivitas yang tercatat.
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
        
        <!-- Pagination Links -->
        <?php if($logs->hasPages()): ?>
        <div style="padding: 24px 40px; border-top: 1px solid var(--color-hairline); display: flex; justify-content: flex-end; background: #fafafa;">
            <?php echo e($logs->links()); ?>

        </div>
        <?php endif; ?>
    </div>

</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\coding\laravel\Scoola\resources\views/admin/logs/index.blade.php ENDPATH**/ ?>