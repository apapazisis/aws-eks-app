<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Σύνδεση · {{ config('app.name', 'Laravel') }}</title>

        <style>
            :root {
                --bg: #f6f8fa; --panel: #ffffff; --border: #d1d9e0;
                --text: #1f2328; --muted: #59636e; --accent: #0969da;
                --btn: #1f2328; --btn-text: #ffffff; --danger: #d1242f;
            }

            @media (prefers-color-scheme: dark) {
                :root {
                    --bg: #0d1117; --panel: #151b23; --border: #3d444d;
                    --text: #f0f6fc; --muted: #9198a1; --accent: #4493f8;
                    --btn: #f0f6fc; --btn-text: #0d1117; --danger: #ff7b72;
                }
            }

            * { box-sizing: border-box; }

            body {
                margin: 0; padding: 2rem 1rem 4rem;
                min-height: 100vh;
                display: flex; align-items: center; justify-content: center;
                background: var(--bg); color: var(--text);
                font-family: ui-sans-serif, system-ui, -apple-system, "Segoe UI", sans-serif;
                font-size: 14px; line-height: 1.5;
                -webkit-font-smoothing: antialiased;
            }

            .wrap { width: 100%; max-width: 340px; }

            .brand {
                display: flex; flex-direction: column; align-items: center;
                gap: .5rem; margin-bottom: 1.5rem; text-align: center;
            }

            h1 { font-size: 1.25rem; font-weight: 600; margin: 0; }
            .subtitle { color: var(--muted); margin: 0; }

            .card {
                background: var(--panel); border: 1px solid var(--border);
                border-radius: 10px; padding: 1.25rem;
            }

            .alert {
                border: 1px solid var(--danger); color: var(--danger);
                border-radius: 6px; padding: .7rem .85rem; margin-bottom: 1rem;
            }

            .field { margin-bottom: 1rem; }

            .field-header {
                display: flex; align-items: baseline; justify-content: space-between;
                gap: .5rem; margin-bottom: .35rem;
            }

            label { font-weight: 500; }

            input[type="email"],
            input[type="password"] {
                width: 100%; padding: .55rem .75rem;
                border: 1px solid var(--border); border-radius: 6px;
                background: var(--bg); color: var(--text); font: inherit;
            }

            input[type="email"]:focus,
            input[type="password"]:focus {
                outline: none; border-color: var(--accent);
                box-shadow: 0 0 0 3px rgba(9, 105, 218, .25);
            }

            input.is-invalid { border-color: var(--danger); }

            .field-error { color: var(--danger); font-size: 12px; margin: .35rem 0 0; }

            .remember {
                display: flex; align-items: center; gap: .45rem;
                color: var(--muted); margin-bottom: 1rem;
            }

            .remember input { margin: 0; }

            .btn {
                display: flex; align-items: center; justify-content: center; gap: .5rem;
                width: 100%; padding: .6rem .9rem;
                border-radius: 6px; border: 1px solid transparent;
                background: var(--btn); color: var(--btn-text);
                font: inherit; font-weight: 600; text-decoration: none; cursor: pointer;
            }

            .btn:hover { opacity: .88; }

            .btn-ghost {
                background: transparent; color: var(--text);
                border-color: var(--border); font-weight: 500;
            }

            .divider {
                display: flex; align-items: center; gap: .75rem;
                color: var(--muted); font-size: 12px; margin: 1rem 0;
            }

            .divider::before, .divider::after {
                content: ""; flex: 1; height: 1px; background: var(--border);
            }

            .link { color: var(--accent); text-decoration: none; font-size: 12px; }
            .link:hover { text-decoration: underline; }
        </style>
    </head>
    <body>
        <div class="wrap">
            <div class="brand">
                <h1>Σύνδεση</h1>
                <p class="subtitle">Συνδέσου στο {{ config('app.name', 'Laravel') }}.</p>
            </div>

            @if (session('error'))
                <div class="alert">{{ session('error') }}</div>
            @endif

            @if (session('status'))
                <div class="alert" style="border-color: var(--border); color: var(--muted);">
                    {{ session('status') }}
                </div>
            @endif

            <div class="card">
                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="field">
                        <div class="field-header">
                            <label for="email">Email</label>
                        </div>
                        <input
                            type="email"
                            id="email"
                            name="email"
                            value="{{ old('email') }}"
                            required
                            autofocus
                            autocomplete="email"
                            placeholder="you@example.com"
                            class="@error('email') is-invalid @enderror"
                        >
                        @error('email')
                            <p class="field-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="field">
                        <div class="field-header">
                            <label for="password">Κωδικός</label>
                        </div>
                        <input
                            type="password"
                            id="password"
                            name="password"
                            required
                            autocomplete="current-password"
                            class="@error('password') is-invalid @enderror"
                        >
                        @error('password')
                            <p class="field-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <label class="remember" for="remember">
                        <input type="checkbox" id="remember" name="remember" value="1" @checked(old('remember'))>
                        Να με θυμάσαι
                    </label>

                    <button type="submit" class="btn">Σύνδεση</button>
                </form>
            </div>
        </div>
    </body>
</html>
