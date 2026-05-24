<?php $__env->startSection('content'); ?>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Fredoka+One&family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">

<style>
    /* Variabel Warna Manga-Pop */
    :root {
        --midnight: #1E1B29;
        --sakura: #FF7675;
        --cyber: #00CEC9;
        --cosmo: #6C5CE7;
        --gold: #FDCB6E;
        --mochi: #FAF9FF;
        --white: #FFFFFF;
    }

    /* Font Classes */
    .font-anime-title { font-family: 'Fredoka One', cursive; }
    .font-anime-body { font-family: 'Nunito', sans-serif; }

    /* Aturan Utama Visual: Card Manga-Pop (Neobrutalism) */
    .manga-card {
        background-color: var(--white);
        border: 4px solid var(--midnight);
        border-radius: 16px;
        box-shadow: 8px 8px 0px 0px var(--midnight);
        padding: 40px;
        position: relative;
        color: var(--midnight);
    }

    /* Varian Warna Card */
    .manga-card-cosmo { background-color: var(--cosmo); color: var(--white); }
    .manga-card-gold { background-color: var(--gold); }
    .manga-card-cyber { background-color: var(--cyber); }

    /* Efek Hover Mekanis */
    .manga-hover-effect { transition: all 0.2s ease; }
    .manga-hover-effect:hover {
        transform: translate(-2px, -2px);
        box-shadow: 10px 10px 0px 0px var(--midnight);
    }
    .manga-hover-effect:active {
        transform: translate(2px, 2px);
        box-shadow: 2px 2px 0px 0px var(--midnight);
    }

    /* Input & Select Neobrutalism */
    .manga-input {
        width: 100%;
        padding: 16px;
        font-family: 'Nunito', sans-serif;
        font-weight: 700;
        font-size: 15px;
        background-color: var(--mochi);
        border: 3px solid var(--midnight);
        border-radius: 12px;
        box-shadow: 4px 4px 0px 0px var(--midnight);
        outline: none;
        transition: all 0.2s ease;
        color: var(--midnight);
    }
    .manga-input:focus {
        background-color: var(--white);
        transform: translate(-2px, -2px);
        box-shadow: 6px 6px 0px 0px var(--midnight);
    }

    /* Table Styling Neobrutalism */
    .manga-table { width: 100%; border-collapse: collapse; min-width: 800px; }
    .manga-table th {
        background-color: var(--gold);
        color: var(--midnight);
        padding: 20px;
        font-family: 'Fredoka One', cursive;
        font-size: 18px;
        border-bottom: 4px solid var(--midnight);
        border-right: 3px solid var(--midnight);
        text-align: left;
    }
    .manga-table th:last-child { border-right: none; }
    .manga-table td {
        padding: 20px;
        font-family: 'Nunito', sans-serif;
        font-weight: 700;
        border-bottom: 3px solid var(--midnight);
        border-right: 3px solid var(--midnight);
        color: var(--midnight);
    }
    .manga-table td:last-child { border-right: none; }
    .manga-table tr:hover td { background-color: var(--mochi); }
    .manga-table tr:last-child td { border-bottom: none; }

    /* Button Edit (Sakura) */
    .manga-btn-edit {
        display: inline-block;
        background-color: var(--sakura);
        color: var(--white);
        font-family: 'Fredoka One', cursive;
        text-decoration: none;
        padding: 8px 24px;
        border: 3px solid var(--midnight);
        border-radius: 12px;
        box-shadow: 4px 4px 0px 0px var(--midnight);
        transition: all 0.2s ease;
        letter-spacing: 1px;
    }
    .manga-btn-edit:hover {
        transform: translate(-2px, -2px);
        box-shadow: 6px 6px 0px 0px var(--midnight);
    }

    /* Badges */
    .manga-badge {
        background-color: var(--cyber);
        color: var(--midnight);
        border: 2px solid var(--midnight);
        font-weight: 800;
        font-size: 11px;
        padding: 6px 12px;
        border-radius: 8px;
        box-shadow: 2px 2px 0px 0px var(--midnight);
        text-transform: uppercase;
        display: inline-block;
    }
</style>

<div style="display: flex; flex-direction: column; gap: 32px; padding-bottom: 40px;" class="font-anime-body">
    
    <div class="manga-card manga-card-cosmo">
        <span style="position: absolute; top: -16px; right: 32px; background: var(--gold); color: var(--midnight); border: 3px solid var(--midnight); font-weight: 800; font-size: 12px; padding: 6px 16px; border-radius: 8px; transform: rotate(5deg); box-shadow: 3px 3px 0px var(--midnight);">
            ( ≧◡≦ ) KAWAII ✨
        </span>
        
        <span style="display: inline-block; font-weight: 800; color: var(--gold); font-size: 12px; text-transform: uppercase; letter-spacing: 0.15em; margin-bottom: 12px; background: var(--midnight); padding: 4px 12px; border-radius: 6px; box-shadow: 2px 2px 0px var(--gold);">Manajemen Sumber Daya</span>
        
        <h1 class="font-anime-title" style="font-size: 48px; margin: 0 0 16px 0; letter-spacing: 1px; text-shadow: 4px 4px 0px var(--midnight); -webkit-text-stroke: 2px var(--midnight);">DATA GURU</h1>
        
        <p style="font-weight: 700; font-size: 16px; max-width: 600px; background: var(--white); color: var(--midnight); padding: 16px; border-radius: 12px; border: 3px solid var(--midnight); box-shadow: 4px 4px 0px var(--midnight); margin: 0;">
            Kelola seluruh data guru yang terdaftar di sistem Scoola, termasuk penugasan mata pelajaran dan akun akses. 🚀
        </p>
    </div>

    <div class="fab-container">
        <a href="<?php echo e(route('guru.create')); ?>" class="btn-fab" title="Tambah Guru">
            <i class="bi bi-plus-lg" style="font-size: 28px;"></i>
            <span class="fab-label">Tambah Guru Baru</span>
        </a>
    </div>

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(340px, 1fr)); gap: 32px;">
        <div class="manga-card manga-card-gold manga-hover-effect" style="display: flex; align-items: center; gap: 24px;">
            <div style="width: 72px; height: 72px; background: var(--white); border: 4px solid var(--midnight); border-radius: 50%; display: flex; align-items: center; justify-content: center; box-shadow: 4px 4px 0px var(--midnight);">
                <i class="bi bi-person-badge" style="font-size: 32px; color: var(--midnight);"></i>
            </div>
            <div>
                <div style="font-weight: 800; font-size: 11px; text-transform: uppercase; letter-spacing: 0.2em; background: var(--white); display: inline-block; padding: 4px 8px; border: 2px solid var(--midnight); border-radius: 6px; margin-bottom: 8px;">TOTAL DATA GURU</div>
                <div class="font-anime-title" style="font-size: 56px; color: var(--white); text-shadow: 4px 4px 0px var(--midnight); -webkit-text-stroke: 2px var(--midnight); line-height: 1;">
                    <?php echo e($guru->count()); ?>

                </div>
            </div>
        </div>

        <div class="manga-card manga-card-cyber manga-hover-effect" style="display: flex; align-items: center; gap: 24px;">
            <div style="width: 72px; height: 72px; background: var(--white); border: 4px solid var(--midnight); border-radius: 50%; display: flex; align-items: center; justify-content: center; box-shadow: 4px 4px 0px var(--midnight);">
                <i class="bi bi-shield-check" style="font-size: 32px; color: var(--midnight);"></i>
            </div>
            <div>
                <div style="font-weight: 800; font-size: 11px; text-transform: uppercase; letter-spacing: 0.2em; background: var(--white); display: inline-block; padding: 4px 8px; border: 2px solid var(--midnight); border-radius: 6px; margin-bottom: 8px;">AKSES SISTEM AKTIF</div>
                <div class="font-anime-title" style="font-size: 56px; color: var(--white); text-shadow: 4px 4px 0px var(--midnight); -webkit-text-stroke: 2px var(--midnight); line-height: 1;">
                    <?php echo e($guru->whereNotNull('user_id')->count()); ?>

                </div>
            </div>
        </div>
    </div>


    <div class="manga-card" style="display: flex; flex-wrap: wrap; gap: 32px; align-items: flex-end;">
        <span style="position: absolute; top: -16px; left: -16px; background: var(--sakura); color: var(--white); border: 3px solid var(--midnight); font-weight: 800; font-size: 12px; padding: 6px 12px; border-radius: 8px; transform: rotate(-5deg); box-shadow: 3px 3px 0px var(--midnight);">
            🔍 Let's Search!
        </span>

        <div style="flex: 1; min-width: 250px;">
            <label style="font-weight: 800; font-size: 12px; text-transform: uppercase; letter-spacing: 1px; display: block; margin-bottom: 12px; color: var(--midnight);">Pencarian Direktori</label>
            <input type="text" id="searchInput" class="manga-input" placeholder="CARI NAMA ATAU NIP...">
        </div>
        <div style="width: 100%; max-width: 320px;">
            <label style="font-weight: 800; font-size: 12px; text-transform: uppercase; letter-spacing: 1px; display: block; margin-bottom: 12px; color: var(--midnight);">Spesialisasi</label>
            <select id="mapelFilter" class="manga-input" style="cursor: pointer; appearance: none;">
                <option value="">✨ Semua Mata Pelajaran</option>
                <?php
                    $allMapels = $guru->flatMap(fn($g) => $g->mapels)->unique('kd_mapel');
                ?>
                <?php $__currentLoopData = $allMapels->sortBy('nama_mapel'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $m): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($m->kd_mapel); ?>"><?php echo e($m->nama_mapel); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>
    </div>

    <div class="manga-card" style="padding: 0; overflow: hidden;">
        <div style="overflow-x: auto;">
            <table class="manga-table">
                <thead>
                    <tr>
                        <th style="padding-left: 32px;">NIP</th>
                        <th>Nama Guru</th>
                        <th>Mata Pelajaran</th>
                        <th>Email</th>
                        <th style="text-align: center; padding-right: 32px;">Aksi</th>
                    </tr>
                </thead>
                <tbody id="guruBody">
                    <?php $__empty_1 = true; $__currentLoopData = $guru; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $g): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="guru-row" 
                        data-name="<?php echo e(strtolower($g->nama_guru)); ?>" 
                        data-nip="<?php echo e(strtolower($g->NIP)); ?>"
                        data-mapels="<?php echo e(json_encode($g->mapels->pluck('kd_mapel'))); ?>">
                        
                        <td style="padding-left: 32px; font-family: monospace; font-size: 16px;"><?php echo e($g->NIP); ?></td>
                        <td style="font-weight: 800; font-size: 17px;"><?php echo e($g->nama_guru); ?></td>
                        <td>
                            <div style="display: flex; flex-wrap: wrap; gap: 8px;">
                                <?php $__currentLoopData = $g->mapels; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $m): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <span class="manga-badge"><?php echo e($m->nama_mapel); ?></span>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </td>
                        <td style="color: #4A5568;"><?php echo e($g->user->email ?? '-'); ?></td>
                        <td style="text-align: center; padding-right: 32px;">
                            <a href="<?php echo e(route('guru.edit', $g->NIP)); ?>" class="manga-btn-edit">
                                Edit ✏️
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="5" style="text-align: center; padding: 80px; font-weight: 800; font-size: 18px; color: #718096; background: var(--mochi);">
                            (′·_·`) Belum ada data guru ditemukan.
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>

<script>
    const searchInput = document.getElementById('searchInput');
    const mapelFilter = document.getElementById('mapelFilter');
    const rows = document.querySelectorAll('.guru-row');

    function filterTable() {
        const q = searchInput.value.toLowerCase().trim();
        const mapel = mapelFilter.value;

        rows.forEach(row => {
            const name = row.dataset.name;
            const nip = row.dataset.nip;
            const mapels = JSON.parse(row.dataset.mapels);

            const matchSearch = !q || name.includes(q) || nip.includes(q);
            const matchMapel = !mapel || mapels.includes(mapel);

            if(matchSearch && matchMapel) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }

    searchInput.addEventListener('input', filterTable);
    mapelFilter.addEventListener('change', filterTable);
</script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\coding\laravel\Scoola\resources\views/admin/guru/guru-index.blade.php ENDPATH**/ ?>