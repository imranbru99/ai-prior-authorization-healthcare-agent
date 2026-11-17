<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'AI Prior Authorization Agent') — ImranDevBD</title>
    <meta name="description" content="@yield('meta_description', 'AI prior authorization healthcare agent that gathers clinical evidence, submits payer requests, tracks status, and reduces denial turnaround time.')">
    <meta name="author" content="Imran Ahmed">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;700;800&family=Source+Serif+4:opsz,wght@8..60,600;8..60,700&display=swap" rel="stylesheet">
    <style>
        :root {
            --accent: {{ $accent ?? '#0E7490' }};
            --ink: #134e4a;
            --muted: #475569;
            --bg: #ecfeff;
            --card: #ffffff;
        }
        * { box-sizing: border-box; }
        body {
            margin: 0;
            font-family: Manrope, system-ui, sans-serif;
            color: var(--ink);
            background:
                radial-gradient(1100px 480px at 8% -12%, rgba(14, 116, 144, 0.22), transparent 58%),
                radial-gradient(900px 420px at 100% 0%, rgba(45, 212, 191, 0.14), transparent 55%),
                linear-gradient(180deg, #f0fdfa 0%, #e0f2fe 100%);
            min-height: 100vh;
        }
        a { color: var(--accent); text-decoration: none; }
        .wrap { width: min(1120px, calc(100% - 2rem)); margin: 0 auto; }
        header {
            display: flex; align-items: center; justify-content: space-between;
            padding: 1.25rem 0; gap: 1rem; flex-wrap: wrap;
        }
        .brand {
            font-family: "Source Serif 4", Georgia, serif;
            font-size: 1.35rem; font-weight: 700; color: var(--ink);
        }
        nav { display: flex; gap: 1rem; flex-wrap: wrap; align-items: center; }
        nav a { color: var(--muted); font-weight: 500; }
        nav a:hover { color: var(--accent); }
        .btn {
            display: inline-flex; align-items: center; justify-content: center;
            background: var(--accent); color: #fff !important; border-radius: 999px;
            padding: 0.7rem 1.2rem; font-weight: 700; border: 0;
        }
        .btn-outline {
            background: transparent; color: var(--accent) !important;
            border: 1.5px solid var(--accent);
        }
        .hero { padding: 3.5rem 0 2.5rem; display: grid; gap: 1.25rem; }
        .hero h1 {
            font-family: "Source Serif 4", Georgia, serif;
            font-size: clamp(2.1rem, 5vw, 3.4rem);
            line-height: 1.1; margin: 0; max-width: 18ch;
        }
        .hero p { max-width: 58ch; color: var(--muted); font-size: 1.1rem; line-height: 1.6; margin: 0; }
        .cta-row { display: flex; gap: 0.75rem; flex-wrap: wrap; margin-top: 0.5rem; }
        .panel {
            background: var(--card);
            border: 1px solid rgba(14, 116, 144, 0.14);
            border-radius: 1.25rem;
            padding: 1.5rem;
            box-shadow: 0 18px 40px rgba(19, 78, 74, 0.06);
        }
        .grid { display: grid; gap: 1rem; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); }
        .section { padding: 2rem 0 3rem; }
        .section h2 {
            font-family: "Source Serif 4", Georgia, serif;
            font-size: clamp(1.6rem, 3vw, 2.2rem);
            margin: 0 0 0.75rem;
        }
        .muted { color: var(--muted); line-height: 1.6; }
        .pill {
            display: inline-block; padding: 0.2rem 0.7rem; border-radius: 999px;
            background: rgba(14, 116, 144, 0.12); color: var(--accent); font-size: 0.85rem; font-weight: 700;
        }
        footer {
            border-top: 1px solid rgba(14, 116, 144, 0.12);
            padding: 2rem 0 3rem; color: var(--muted); font-size: 0.95rem;
        }
        .dash-shot {
            width: 100%; border-radius: 1rem; border: 1px solid rgba(14, 116, 144, 0.15);
            box-shadow: 0 24px 50px rgba(19, 78, 74, 0.12);
        }
        table.pricing { width: 100%; border-collapse: collapse; }
        table.pricing th, table.pricing td {
            text-align: left; padding: 0.85rem 0.75rem;
            border-bottom: 1px solid rgba(14, 116, 144, 0.12);
        }
        @media (max-width: 720px) { .hero { padding-top: 2rem; } }
    </style>
</head>
<body>
    <div class="wrap">
        <header>
            <a class="brand" href="{{ route('home') }}">PriorAuth AI</a>
            <nav>
                <a href="{{ route('features') }}">Features</a>
                <a href="{{ route('modules') }}">Modules</a>
                <a href="{{ route('pricing') }}">Pricing</a>
                <a href="{{ route('demo') }}">Demo</a>
                <a class="btn" href="/admin">Admin Login</a>
            </nav>
        </header>

        @yield('content')

        <footer>
            <p><strong>AI Prior Authorization Agent</strong> by <a href="https://imrandev.bd">Imran Ahmed</a> — built for health systems and RCM teams.</p>
            <p>Contact: <a href="mailto:me@imrandev.bd">me@imrandev.bd</a> · <a href="https://imrandev.bd/contact">imrandev.bd/contact</a></p>
        </footer>
    </div>
</body>
</html>
