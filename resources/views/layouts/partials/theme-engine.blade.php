{{-- Shared Theme Engine JavaScript --}}
<script>
    const THEME_KEY = 'scoola-theme';

    function scoolaApplyTheme(theme) {
        document.documentElement.setAttribute('data-theme', theme);
        localStorage.setItem(THEME_KEY, theme);

        const icon  = document.getElementById('scoolaThemeIcon');
        const label = document.getElementById('scoolaThemeLabel');
        if (!icon || !label) return;

        if (theme === 'dark') {
            icon.className  = 'bi bi-moon-stars-fill';
            icon.style.fontSize = '12px';
            label.textContent = 'Light';
        } else {
            icon.className  = 'bi bi-sun-fill';
            icon.style.fontSize = '13px';
            label.textContent = 'Dark';
        }
    }

    function scoolaToggleTheme() {
        const current = document.documentElement.getAttribute('data-theme') || 'dark';
        scoolaApplyTheme(current === 'dark' ? 'light' : 'dark');
    }

    // Init on load
    (function() {
        var t = localStorage.getItem('scoola-theme') || 'dark';
        scoolaApplyTheme(t);
    })();

    // Mobile sidebar toggle
    function toggleSidebar() {
        document.querySelector('.sidebar')?.classList.toggle('open');
        document.querySelector('.sidebar-overlay')?.classList.toggle('open');
    }

    document.querySelector('.sidebar-overlay')?.addEventListener('click', toggleSidebar);
</script>
