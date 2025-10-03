<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Loka Loka')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Slab:wght@700&family=Roboto:wght@400;700&family=Open+Sans:wght@400&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        :root {
            --brand-primary: #5c6641;
            --brand-primary-dark: #4a5335;
            --brand-accent: #8B9D46;
            --text-darker: #1b1b18;
        }
        .font-roboto-slab { font-family: 'Roboto Slab', serif; }
        .font-open-sans { font-family: 'Open Sans', sans-serif; }
        .text-green-darker { color: var(--text-darker); }
        .bg-primary { background-color: var(--brand-primary); }
        .hover\:bg-primary-dark:hover { background-color: var(--brand-primary-dark); }
        .text-accent { color: var(--brand-accent); }
        .focus-ring { outline: none; box-shadow: 0 0 0 3px rgba(139,157,70,.35); }
        .btn { display:inline-flex; align-items:center; justify-content:center; gap:.5rem; border-radius:.75rem; padding:.625rem 1rem; font-weight:600; transition:.15s; }
        .btn-primary { background: var(--brand-primary); color:#fff; }
        .btn-primary:hover { background: var(--brand-primary-dark); }
        .btn-secondary { background:#f3f4f6; color:#111827; }
        .btn-secondary:hover { background:#e5e7eb; }
        .btn-link { color: var(--brand-primary); }
        .btn-link:hover { color: var(--brand-primary-dark); text-decoration: underline; }
        .card { background:#fff; border:1px solid #e5e7eb; border-radius:1rem; box-shadow:0 1px 2px rgba(0,0,0,.04); }
        .container-page { max-width: 80rem; margin: 0 auto; padding: 2rem 1.5rem; }
        .alert { border-radius:.75rem; padding: .75rem 1rem; }
        .alert-success { background:#d1fae5; color:#065f46; border:1px solid #a7f3d0; }
        .alert-error { background:#fee2e2; color:#991b1b; border:1px solid #fecaca; }
    </style>

    @stack('head')
</head>
<body class="bg-[#fffceb] text-green-darker font-open-sans">
    @include('layouts.header')

    @if (session('success'))
        <div class="container-page">
            <div class="alert alert-success">{{ session('success') }}</div>
        </div>
    @endif
    @if ($errors->any())
        <div class="container-page">
            <div class="alert alert-error">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        </div>
    @endif

    <main class="container-page">
        @yield('content')
    </main>

    @include('layouts.footer')

    @stack('scripts')
</body>
</html>