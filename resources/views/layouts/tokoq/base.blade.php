<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'TokoQ' }}</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
    <link href="{{ asset('template/tokoq_design_system/responsive.css') }}" rel="stylesheet">
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        'inverse-primary': '#b8cf8c',
                        'tertiary-fixed': '#dae9ac',
                        'surface-bright': '#f8fbea',
                        'primary-fixed-dim': '#b8cf8c',
                        'primary-fixed': '#d3eba6',
                        'on-surface': '#191d13',
                        'inverse-on-surface': '#f0f2e2',
                        'surface-tint': '#51652e',
                        'outline': '#75786b',
                        'background': '#f8fbea',
                        'surface-variant': '#e1e4d4',
                        'on-secondary-container': '#596841',
                        'on-tertiary': '#ffffff',
                        'secondary-fixed-dim': '#bccd9e',
                        'on-error-container': '#93000a',
                        'inverse-surface': '#2e3227',
                        'secondary': '#55633d',
                        'surface-dim': '#d9dccb',
                        'secondary-fixed': '#d8e9b9',
                        'error-container': '#ffdad6',
                        'surface-container-highest': '#e1e4d4',
                        'on-tertiary-fixed': '#161f00',
                        'primary-container': '#576b33',
                        'surface-container-high': '#e7ead9',
                        'on-background': '#191d13',
                        'on-error': '#ffffff',
                        'surface-container-low': '#f2f5e4',
                        'tertiary': '#445122',
                        'secondary-container': '#d5e6b6',
                        'on-primary-container': '#d3eba5',
                        'tertiary-container': '#5c6938',
                        'on-secondary-fixed': '#131f02',
                        'outline-variant': '#c5c8b9',
                        'on-secondary': '#ffffff',
                        'on-primary-fixed': '#131f00',
                        'on-secondary-fixed-variant': '#3d4b28',
                        'surface': '#f8fbea',
                        'tertiary-fixed-dim': '#becd92',
                        'surface-container-lowest': '#ffffff',
                        'on-tertiary-container': '#d9e8aa',
                        'on-surface-variant': '#45483d',
                        'surface-container': '#edefdf',
                        'on-primary': '#ffffff',
                        'primary': '#40521d',
                        'on-primary-fixed-variant': '#3a4d18',
                        'error': '#ba1a1a',
                        'on-tertiary-fixed-variant': '#3f4b1d'
                    },
                    spacing: {
                        'container-padding': '32px',
                        'section-margin': '48px',
                        'gutter': '24px',
                        'unit': '8px',
                        'card-gap': '24px'
                    },
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', 'sans-serif']
                    },
                    fontSize: {
                        'h2-mobile': ['24px', {lineHeight: '1.3', fontWeight: '700'}],
                        'h1-mobile': ['28px', {lineHeight: '1.2', fontWeight: '700'}],
                        'body-md': ['16px', {lineHeight: '1.6', fontWeight: '400'}],
                        'body-lg': ['18px', {lineHeight: '1.6', fontWeight: '400'}],
                        'body-sm': ['14px', {lineHeight: '1.5', fontWeight: '400'}],
                        'h1': ['40px', {lineHeight: '1.2', letterSpacing: '-0.02em', fontWeight: '700'}],
                        'h3': ['24px', {lineHeight: '1.4', fontWeight: '600'}],
                        'label-caps': ['12px', {lineHeight: '1.2', letterSpacing: '0.05em', fontWeight: '700'}],
                        'h2': ['32px', {lineHeight: '1.3', letterSpacing: '-0.01em', fontWeight: '700'}]
                    }
                }
            }
        };
    </script>
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: #f8fbea;
            color: #191d13;
        }

        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }

        .paper-card {
            background: rgba(255, 255, 255, 0.92);
            border: 1px solid #dde3d2;
            box-shadow: 0 18px 40px -28px rgba(73, 89, 42, 0.5);
        }

        .paper-elevation {
            box-shadow: 0 20px 50px -30px rgba(73, 89, 42, 0.35);
        }

        .glass-paper {
            background: rgba(255, 255, 255, 0.84);
            backdrop-filter: blur(12px);
        }

        .matcha-gradient {
            background: linear-gradient(135deg, #f8fbea 0%, #edefdf 55%, #d9dccb 100%);
        }

        .digital-twin-gradient {
            background: radial-gradient(circle at top right, rgba(211, 235, 166, 0.6), rgba(255, 255, 255, 0.95) 45%, rgba(248, 251, 234, 0.92) 100%);
        }

        .ai-card-gradient {
            background: linear-gradient(145deg, rgba(255, 255, 255, 0.95), rgba(242, 245, 228, 0.96));
        }
    </style>
    @stack('styles')
</head>
<body class="{{ $bodyClass ?? '' }}">
    @yield('body')
    <script src="{{ asset('template/tokoq_design_system/responsive.js') }}"></script>
    @stack('scripts')
</body>
</html>
