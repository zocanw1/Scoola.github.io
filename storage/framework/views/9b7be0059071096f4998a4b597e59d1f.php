<?php $__env->startSection('content'); ?>

<div style="display: flex; flex-direction: column; gap: var(--spacing-md);">
    
    <!-- Header Card -->
    <div class="card" style="background: #ffffff; padding: 32px; border-radius: 16px; border: 1px solid var(--color-hairline);">
        <div class="editorial-header" style="margin: 0;">
            <span class="eyebrow" style="color: var(--color-stone); text-transform: uppercase; letter-spacing: 0.1em; font-size: 11px; font-weight: 700;">Manajemen Data</span>
            <h1 class="display-title" style="font-size: 48px; font-weight: 400; letter-spacing: var(--tracking-tighter); margin: 8px 0 24px 0; text-transform: uppercase;">Pendaftaran Siswa</h1>
            <p class="text-body" style="color: var(--color-graphite); max-width: 600px; font-size: 16px; line-height: 1.5; margin: 0;">
                Daftarkan siswa baru ke dalam sistem akademik Scoola. Pastikan seluruh data yang dimasukkan telah valid.
            </p>
        </div>
    </div>

    <?php if($errors->any()): ?>
        <div class="card" style="background: #ffffff; padding: 24px; border: 2px solid var(--color-ink); border-radius: 12px;">
            <div class="text-micro-caps" style="color: var(--color-ink); margin-bottom: 16px; font-weight: 700;">Ditemukan Kesalahan Validasi:</div>
            <ul style="padding-left: 20px; margin: 0; color: var(--color-ink); font-size: 14px; line-height: 1.8;">
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><?php echo e($error); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
    <?php endif; ?>

    <!-- Form Card -->
    <div class="card" style="background: #ffffff; padding: 48px; border-radius: 16px; border: 1px solid var(--color-hairline); margin-bottom: var(--spacing-section);">
        <form method="POST" action="<?php echo e(route('siswa.store')); ?>" style="max-width: 720px;">
            <?php echo csrf_field(); ?>

            <div style="margin-bottom: 48px;">
                <label class="text-micro-caps" style="display: block; margin-bottom: 12px; color: var(--color-stone); font-weight: 700;">NIS (Nomor Induk Siswa)</label>
                <input type="text" name="nis" class="form-field" placeholder="Contoh: 12345678" required value="<?php echo e(old('nis')); ?>">
            </div>

            <div style="margin-bottom: 48px;">
                <label class="text-micro-caps" style="display: block; margin-bottom: 12px; color: var(--color-stone); font-weight: 700;">Nama Lengkap Siswa</label>
                <input type="text" name="nama" class="form-field" placeholder="Masukkan nama lengkap siswa" required value="<?php echo e(old('nama')); ?>">
            </div>

            <div style="margin-bottom: 48px;">
                <label class="text-micro-caps" style="display: block; margin-bottom: 12px; color: var(--color-stone); font-weight: 700;">Penempatan Kelas</label>
                <div style="position: relative;">
                    <select name="kelas" class="form-field" style="cursor: pointer; appearance: none; text-transform: uppercase;" required>
                        <option value="" disabled <?php echo e(old('kelas') ? '' : 'selected'); ?>>PILIH KELAS</option>
                        <option value="XI-SIJA 1" <?php echo e(old('kelas') == 'XI-SIJA 1' ? 'selected' : ''); ?>>XI-SIJA 1</option>
                        <option value="XI-SIJA 2" <?php echo e(old('kelas') == 'XI-SIJA 2' ? 'selected' : ''); ?>>XI-SIJA 2</option>
                    </select>
                    <div style="position: absolute; right: 0; top: 50%; transform: translateY(-50%); pointer-events: none; font-size: 12px; color: var(--color-stone);">&darr;</div>
                </div>
            </div>

            <div style="margin-bottom: 48px;">
                <label class="text-micro-caps" style="display: block; margin-bottom: 12px; color: var(--color-stone); font-weight: 700;">Alamat Email Institusi</label>
                <input type="email" name="email" class="form-field" placeholder="nama@sekolah.com" required value="<?php echo e(old('email')); ?>">
            </div>

            <div style="margin-bottom: 48px;">
                <label class="text-micro-caps" style="display: block; margin-bottom: 12px; color: var(--color-stone); font-weight: 700;">Password Sementara</label>
                <input type="password" name="password" class="form-field" placeholder="••••••••" required>
                <small style="color: var(--color-stone); margin-top: 12px; display: block; font-size: 10px; text-transform: uppercase; letter-spacing: 1px; font-weight: 700;">Minimum 8 karakter untuk keamanan</small>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-primary" style="height: 56px; padding: 0 48px; font-size: 15px;">Simpan Data Siswa</button>
                <a href="<?php echo e(route('siswa.index')); ?>" class="btn-ghost" style="text-decoration: none; height: 56px; padding: 0 32px; display: inline-flex; align-items: center; font-size: 13px;">Batal</a>
            </div>
        </form>
    </div>

</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\coding\laravel\Scoola\resources\views/admin/siswa/siswa-create.blade.php ENDPATH**/ ?>