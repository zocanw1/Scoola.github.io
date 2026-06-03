# Scoola Codex Agents

Instruksi ini adalah entrypoint project-local untuk Codex IDE/CLI di `C:\coding\laravel\Scoola`.

Codex IDE harus membaca file ini saat project dibuka, lalu memakai `.codex/config.toml` dan file `.codex/agents/*.toml` sebagai definisi subagent lokal. Konfigurasi ini bukan pengganti runtime config utama Codex di `~/.codex/config.toml`.

## Default Coordinator Mode

Untuk project Scoola, setiap perintah perubahan aplikasi harus melewati `default_coordinator` lebih dulu. Ini adalah aturan kerja repo, bukan sekadar gaya prompt.

Jika user memberi satu perintah besar seperti `Ganti desain ini menjadi seperti Panduan Desain`, Codex default harus otomatis:

1. Membaca `.codex/agents/default_coordinator.toml`.
2. Menentukan subagent yang relevan.
3. Membagi tugas ke subagent yang sesuai jika tool multi-agent tersedia.
4. Jika tool multi-agent tidak tersedia, menjalankan peran subagent secara berurutan berdasarkan file TOML.
5. Menggabungkan hasil subagent menjadi satu keputusan kerja.
6. Menjaga agar fitur, data, route, form, role, database flow, dan keamanan tidak hilang.

Aturan ini adalah otorisasi repo-level untuk memakai delegasi/subagent otomatis pada tugas besar, audit, redesign, refactor, debugging lintas area, atau perubahan yang berisiko ke production.

## Cara Pakai

Gunakan prompt seperti:

- `ganti desain admin menjadi seperti Panduan Desain`
- `rombak halaman ini tapi jangan hilangkan fitur`
- `gunakan agent ui_designer untuk audit admin layout`
- `gunakan agent bug_hunter untuk cek error dashboard`
- `gunakan agent database_checker untuk cek relasi presensi`
- `gunakan agent security_reviewer untuk audit login dan role`

Saat salah satu agent diminta, Codex harus:

1. Membaca `.codex/config.toml`.
2. Membaca file agent terkait di `.codex/agents/<nama_agent>.toml`.
3. Mengikuti fokus, batasan, dan format output agent tersebut.
4. Tetap mematuhi instruksi utama repo dan permintaan terbaru user.

## Team

| Agent | File | Fokus |
| --- | --- | --- |
| `default_coordinator` | `.codex/agents/default_coordinator.toml` | Agent default yang membagi satu perintah ke subagent relevan lalu menggabungkan hasilnya. |
| `ui_designer` | `.codex/agents/ui_designer.toml` | UI, layout, spacing, warna, responsive, dan desain modern sesuai Panduan Desain. |
| `bug_hunter` | `.codex/agents/bug_hunter.toml` | Bug logic, Blade render error, JavaScript error, route error, dan fitur rusak. |
| `database_checker` | `.codex/agents/database_checker.toml` | Database, migration, model, relasi tabel, query, dan koneksi Supabase/Vercel. |
| `security_reviewer` | `.codex/agents/security_reviewer.toml` | Keamanan login, role, validasi input, CSRF, SQL injection, dan upload file. |

## Aturan Global Scoola

- Jangan hilangkan fitur, data, route, form action, input name, CSRF token, role check, atau flow backend yang sudah berjalan.
- Jangan ubah controller, route, model, migration, database flow, atau file aplikasi lain kecuali user jelas meminta.
- Jangan menjalankan perubahan destructive pada database atau filesystem.
- Jangan push atau deploy kecuali user jelas meminta.
- Untuk tugas UI, prioritaskan perubahan presentasi dan pertahankan perilaku backend.
- Untuk tugas audit, mulai dari file relevan dan beri temuan berbasis file/line jika memungkinkan.
- Untuk validasi, gunakan command yang sesuai dengan dampak perubahan, misalnya `php artisan view:cache`, `npm run build`, atau test Laravel yang relevan.

## Compatibility Note

Hasil cek lokal menunjukkan Codex CLI/IDE memakai runtime config utama dari `~/.codex/config.toml`. Karena itu, `.codex/config.toml` di repo ini dibuat sebagai manifest project-local yang dirujuk oleh `AGENTS.md`, bukan sebagai schema runtime native yang harus dimuat otomatis oleh Codex.


## RULESS FORM USER
Gunakan subagent asli, bukan hanya role prompt.

Spawn agent terpisah sesuai file TOML:
1. ui_designer
2. bug_hunter
3. security_reviewer

Jangan gabungkan bug_hunter dan security_reviewer dalam satu agent.
Tampilkan nama agent yang dibuat dan tugas masing-masing.