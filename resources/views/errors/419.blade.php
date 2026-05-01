<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Page Expired</title>

        <style>
            /*! normalize.css v8.0.1 | MIT License | github.com/necolas/normalize.css */
            html { line-height: 1.15; -webkit-text-size-adjust: 100%; }
            body { margin: 0; }
            a { background-color: transparent; }
            code { font-family: monospace, monospace; font-size: 1em; }

            body {
                font-family: ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, "Noto Sans", sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji";
            }
        </style>

        <style>
            .relative { position: relative; }
            .flex { display: flex; }
            .items-center { align-items: center; }
            .justify-center { justify-content: center; }
            .min-h-screen { min-height: 100vh; }
            .antialiased { -webkit-font-smoothing: antialiased; -moz-osx-font-smoothing: grayscale; }
            .tracking-wider { letter-spacing: 0.05em; }
            .text-gray-500 { color: #6b7280; }
            .uppercase { text-transform: uppercase; }
            .border-r { border-right-width: 1px; }
            .border-gray-400 { border-color: #9ca3af; }
            .px-4 { padding-left: 1rem; padding-right: 1rem; }
            .text-lg { font-size: 1.125rem; line-height: 1.75rem; }
            
            /* Dark mode support to match your app */
            [data-theme="dark"] body { background-color: #0c0c0c; color: #f0f0f0; }
            [data-theme="dark"] .text-gray-500 { color: #9ca3af; }
            [data-theme="dark"] .border-gray-400 { border-color: #4b5563; }
        </style>
    </head>
    <body class="antialiased">
        <script>
            // Sync theme
            const theme = localStorage.getItem('scoola-theme') || 'dark';
            document.documentElement.setAttribute('data-theme', theme);

            // Redirect to login after 1 second
            setTimeout(function() {
                window.location.href = "{{ route('login') }}";
            }, 1000);
        </script>

        <div class="relative flex items-center justify-center min-h-screen">
            <div class="flex items-center">
                <div class="px-4 text-lg text-gray-500 border-r border-gray-400 tracking-wider">
                    419                </div>

                <div class="px-4 text-lg text-gray-500 uppercase tracking-wider">
                    Page Expired                </div>
            </div>
        </div>
    </body>
</html>
