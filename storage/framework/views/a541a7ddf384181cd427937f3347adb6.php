<?php $__env->startSection('content'); ?>

<div style="display: flex; flex-direction: column; gap: var(--spacing-md);">

    <!-- Header Card -->
    <div class="card" style="background: #ffffff; padding: 32px; border-radius: 16px; border: 1px solid var(--color-hairline);">
        <div class="editorial-header" style="margin: 0; display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 24px;">
            <div>
                <span class="eyebrow" style="color: var(--color-stone); text-transform: uppercase; letter-spacing: 0.15em; font-size: 11px; font-weight: 700;">Manajemen Presensi</span>
                <h1 class="display-title" style="font-size: 32px; font-weight: 400; letter-spacing: var(--tracking-tighter); margin: 8px 0 16px 0; text-transform: uppercase;">Rekap Mingguan</h1>
                <p class="text-body" style="color: var(--color-graphite); font-size: 15px; line-height: 1.6; margin: 0; max-width: 600px;">
                    Pilih kelas dan tanggal (salah satu hari dalam minggu) untuk melihat format matriks kehadiran siswa (Senin - Jumat). Anda dapat mencetak/export rekap ini.
                    <?php if(isset($startOfWeek) && isset($endOfWeek)): ?>
                    <br><strong>Minggu: <?php echo e($startOfWeek->format('d M Y')); ?> - <?php echo e($endOfWeek->format('d M Y')); ?></strong>
                    <?php endif; ?>
                </p>
            </div>
            
            <form action="<?php echo e(route('admin.rekap.index')); ?>" method="GET" style="display: flex; gap: 12px; align-items: flex-end;">
                <div style="display: flex; flex-direction: column; gap: 6px;">
                    <label style="font-size: 12px; font-weight: 600; color: var(--color-stone);">Kelas</label>
                    <select name="kelas" class="form-control" style="height: 44px; border-radius: 8px; border: 1px solid var(--color-hairline); min-width: 140px;">
                        <option value="">-- Pilih Kelas --</option>
                        <?php $__currentLoopData = $kelas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($k->nama_kelas); ?>" <?php echo e($selectedKelas == $k->nama_kelas ? 'selected' : ''); ?>><?php echo e($k->nama_kelas); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                
                <div style="display: flex; flex-direction: column; gap: 6px;">
                    <label style="font-size: 12px; font-weight: 600; color: var(--color-stone);">Pilih Tanggal</label>
                    <input type="date" name="tanggal" value="<?php echo e($tanggalInput ?? now()->toDateString()); ?>" class="form-control" style="height: 44px; border-radius: 8px; border: 1px solid var(--color-hairline); padding: 0 12px;">
                </div>
                
                <button type="submit" class="btn-primary" style="height: 44px; padding: 0 24px; border-radius: 8px; font-size: 14px; font-weight: 600; border: none; cursor: pointer;">
                    Tampilkan
                </button>
                
                <?php if($selectedKelas): ?>
                <button type="submit" formaction="<?php echo e(route('admin.rekap.export')); ?>" formmethod="GET" style="height: 44px; padding: 0 24px; border-radius: 8px; font-size: 14px; font-weight: 600; border: 1px solid #107c41; background: #107c41; color: white; cursor: pointer; display: flex; align-items: center; gap: 8px;">
                    <i class="bi bi-file-earmark-excel"></i> Export Excel
                </button>
                <?php endif; ?>
            </form>
        </div>
    </div>

    <?php if(session('error')): ?>
        <div style="border-left: 4px solid #e53e3e; background: #fff5f5; color: #c53030; padding: 16px; border-radius: 8px; font-size: 14px; font-weight: 500;">
            <?php echo e(session('error')); ?>

        </div>
    <?php endif; ?>

    <?php if($selectedKelas): ?>
    <div class="card" style="background: #ffffff; padding: 0; border-radius: 16px; border: 1px solid var(--color-hairline); overflow: hidden;">
        <div style="overflow-x: auto; padding: 24px;">
            <table style="width: 100%; min-width: 1200px; border-collapse: collapse; font-size: 12px; font-family: sans-serif; text-align: center;">
                <thead>
                    <tr>
                        <th rowspan="3" style="border: 1px solid #000; padding: 8px; background: #f8f9fa;">NO</th>
                        <th rowspan="3" style="border: 1px solid #000; padding: 8px; background: #f8f9fa;">NIS</th>
                        <th rowspan="3" style="border: 1px solid #000; padding: 8px; background: #f8f9fa; min-width: 200px; text-align: left;">NAMA SISWA</th>
                        <th rowspan="3" style="border: 1px solid #000; padding: 8px; background: #f8f9fa;">L/P</th>
                        <?php $__currentLoopData = $hariList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $hari): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <th colspan="12" style="border: 1px solid #000; padding: 8px; background: #f8f9fa; font-weight: bold; text-transform: uppercase;">
                                <?php echo e($hari); ?>

                            </th>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tr>
                    <tr>
                        <?php $__currentLoopData = $hariList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $hari): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php for($i = 1; $i <= 12; $i++): ?>
                                <th style="border: 1px solid #000; padding: 4px; background: #fdfdfd;"><?php echo e($i); ?></th>
                            <?php endfor; ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tr>
                    <tr>
                        <?php $__currentLoopData = $hariList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $hari): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <!-- Loop untuk Mapel -->
                            <?php for($i = 1; $i <= 12; $i++): ?>
                                <?php
                                    $mapelTampil = '-';
                                    $guruTampil = '-';
                                    foreach($jadwals as $jadwal) {
                                        if($jadwal->hari === $hari && $jadwal->jam_mulai <= $i && $jadwal->jam_selesai >= $i) {
                                            $mapelTampil = $jadwal->mapel->nama_mapel ?? $jadwal->kd_mapel;
                                            $guruTampil = $jadwal->guru->nama_guru ?? '-';
                                            break;
                                        }
                                    }
                                ?>
                                <th style="border: 1px solid #000; padding: 4px; font-size: 10px; background: #fff; font-weight: normal; vertical-align: top; width: 45px;">
                                    <div style="writing-mode: vertical-rl; text-orientation: mixed; height: 120px; margin: 0 auto; line-height: 1.2;">
                                        <strong><?php echo e($mapelTampil); ?></strong><br>
                                        <span style="color: #666; font-size: 9px;"><?php echo e($guruTampil); ?></span>
                                    </div>
                                </th>
                            <?php endfor; ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $siswas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $siswa): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td style="border: 1px solid #000; padding: 6px;"><?php echo e($index + 1); ?></td>
                            <td style="border: 1px solid #000; padding: 6px;"><?php echo e($siswa->NIS); ?></td>
                            <td style="border: 1px solid #000; padding: 6px; text-align: left;"><?php echo e($siswa->nama_siswa); ?></td>
                            <td style="border: 1px solid #000; padding: 6px;"><?php echo e($siswa->jenis_kelamin ?? 'L'); ?></td>
                            
                            <?php $__currentLoopData = $hariList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $hari): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php for($i = 1; $i <= 12; $i++): ?>
                                    <?php
                                        $hadir = false;
                                        foreach($jadwals as $jadwal) {
                                            if($jadwal->hari === $hari && $jadwal->jam_mulai <= $i && $jadwal->jam_selesai >= $i) {
                                                if(isset($presensiMap[$siswa->NIS][$jadwal->kd_jp]) && $presensiMap[$siswa->NIS][$jadwal->kd_jp] == 'Hadir') {
                                                    $hadir = true;
                                                }
                                                break;
                                            }
                                        }
                                    ?>
                                    <td style="border: 1px solid #000; padding: 6px; text-align: center; vertical-align: middle;">
                                        <?php if($hadir): ?>
                                            <span style="color: #107c41; font-weight: bold; font-size: 14px;">✔</span>
                                        <?php endif; ?>
                                    </td>
                                <?php endfor; ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="<?php echo e(4 + (count($hariList) * 12)); ?>" style="border: 1px solid #000; padding: 16px; text-align: center;">Belum ada data siswa di kelas ini.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php else: ?>
    <!-- Placeholder Empty State -->
    <div class="card" style="background: #ffffff; padding: 64px 32px; border-radius: 16px; border: 1px solid var(--color-hairline); text-align: center; display: flex; flex-direction: column; align-items: center; gap: 24px;">
        <div style="width: 80px; height: 80px; border-radius: 50%; background: var(--color-canvas); color: var(--color-stone); display: flex; align-items: center; justify-content: center; border: 1px solid var(--color-hairline);">
            <i class="bi bi-table" style="font-size: 36px;"></i>
        </div>
        <div>
            <h3 style="font-size: 20px; font-weight: 600; margin: 0 0 8px 0; color: var(--color-ink);">Pilih Kelas</h3>
            <p style="color: var(--color-stone); max-width: 420px; font-size: 14px; line-height: 1.5; margin: 0;">
                Silakan pilih kelas dari menu di atas untuk menampilkan matriks rekap mingguan.
            </p>
        </div>
    </div>
    <?php endif; ?>

</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\coding\laravel\Scoola\resources\views/admin/rekap/index.blade.php ENDPATH**/ ?>