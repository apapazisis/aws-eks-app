<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name', 'Laravel') }}</title>

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
                margin: 0; padding: 0 0 4rem;
                background: var(--bg); color: var(--text);
                font-family: ui-sans-serif, system-ui, -apple-system, "Segoe UI", sans-serif;
                font-size: 14px; line-height: 1.5;
                -webkit-font-smoothing: antialiased;
            }

            .wrap { max-width: 720px; margin: 0 auto; padding: 0 1rem; }

            .topbar {
                background: var(--panel); border-bottom: 1px solid var(--border);
                margin-bottom: 2rem;
            }

            .topbar .wrap {
                display: flex; align-items: center; gap: .75rem;
                padding-top: .75rem; padding-bottom: .75rem;
            }

            .brand { font-weight: 600; }
            .who { flex: 1; color: var(--muted); font-size: 12px; }

            h1 { font-size: 1.5rem; margin: 0 0 .25rem; }
            .subtitle { color: var(--muted); margin: 0 0 1.5rem; }

            .card {
                background: var(--panel); border: 1px solid var(--border);
                border-radius: 10px; padding: 1.25rem; margin-bottom: 1rem;
            }

            .card-title { font-weight: 600; margin: 0 0 .75rem; }

            .row { display: flex; align-items: center; gap: .75rem; flex-wrap: wrap; }

            .btn {
                display: inline-flex; align-items: center; gap: .5rem;
                padding: .5rem .9rem; border-radius: 6px; border: 1px solid transparent;
                background: var(--btn); color: var(--btn-text);
                font: inherit; font-weight: 600; text-decoration: none; cursor: pointer;
            }

            .btn:hover { opacity: .88; }

            .btn-ghost {
                background: transparent; color: var(--text);
                border-color: var(--border); font-weight: 500;
            }

            .btn-danger {
                background: transparent; color: var(--danger);
                border-color: var(--border); font-weight: 500;
            }

            .btn-danger:hover { border-color: var(--danger); opacity: 1; }

            .inline-form { display: inline; margin: 0; }

            .avatar { width: 32px; height: 32px; border-radius: 50%; border: 1px solid var(--border); }

            .badge {
                display: inline-block; padding: .1rem .5rem; border-radius: 999px;
                border: 1px solid var(--border); color: var(--muted); font-size: 12px;
            }

            .search { display: flex; gap: .5rem; }

            input[type="search"] {
                flex: 1; min-width: 0; padding: .55rem .75rem;
                border: 1px solid var(--border); border-radius: 6px;
                background: var(--bg); color: var(--text); font: inherit;
            }

            input[type="search"]:focus { outline: none; border-color: var(--accent); }
            input[type="search"]:disabled { opacity: .6; cursor: not-allowed; }

            ul.results { list-style: none; margin: 1rem 0 0; padding: 0; }
            ul.results li { padding: .85rem 0; border-top: 1px solid var(--border); }
            ul.results li:first-child { border-top: 0; }

            .repo-name { color: var(--accent); font-weight: 600; text-decoration: none; }
            .repo-name:hover { text-decoration: underline; }
            .repo-desc { color: var(--muted); margin: .2rem 0 .4rem; }
            .meta { color: var(--muted); font-size: 12px; display: flex; gap: 1rem; flex-wrap: wrap; }
            .status { color: var(--muted); margin-top: 1rem; }
            .status.error { color: var(--danger); }
            .hint { color: var(--muted); font-size: 12px; margin-top: .75rem; }
        </style>
    </head>
    <body>
        @php
            $user = auth()->user();
            $fullName = trim(($user->first_name ?? '') . ' ' . ($user->last_name ?? ''));
            $githubConnected = (bool) $user->github_id;
        @endphp

        <header class="topbar">
            <div class="wrap">
                <span class="brand">{{ config('app.name', 'Laravel') }}</span>
                <span class="who">{{ $fullName !== '' ? $fullName : $user->email }}</span>

                <a href="{{ route('logout') }}" class="btn btn-danger">Αποσύνδεση</a>
            </div>
        </header>

        <div class="wrap">
            <h1>GitHub Repository Search</h1>
            <p class="subtitle">Συνδέσου με το GitHub και αναζήτησε repositories.</p>

            @if (session('error'))
                <div class="card" style="border-color: var(--danger); color: var(--danger);">
                    {{ session('error') }}
                </div>
            @endif

            @if (session('status'))
                <div class="card" style="border-color: var(--accent);">
                    {{ session('status') }}
                </div>
            @endif

            <div class="card">
                <p class="card-title">Σύνδεση με GitHub</p>

                @if ($githubConnected)
                    <div class="row">
                        @if ($user->avatar_url)
                            <img class="avatar" src="{{ $user->avatar_url }}" alt="">
                        @endif
                        <div style="flex: 1;">
                            <strong>{{ $user->github_name ?: $user->login }}</strong>
                            <div style="color: var(--muted);">&#64;{{ $user->login }}</div>
                        </div>
                        <span class="badge">Συνδεδεμένο</span>

                        <form class="inline-form" method="POST" action="{{ route('github.disconnect') }}">
                            @csrf
                            <button type="submit" class="btn btn-ghost">Αποσύνδεση από GitHub</button>
                        </form>
                    </div>
                @else
                    <div class="row">
                        <a href="{{ route('auth.redirect') }}" class="btn">
                            <svg height="18" width="18" viewBox="0 0 16 16" fill="currentColor" aria-hidden="true">
                                <path d="M8 0C3.58 0 0 3.58 0 8c0 3.54 2.29 6.53 5.47 7.59.4.07.55-.17.55-.38 0-.19-.01-.82-.01-1.49-2.01.37-2.53-.49-2.69-.94-.09-.23-.48-.94-.82-1.13-.28-.15-.68-.52-.01-.53.63-.01 1.08.58 1.23.82.72 1.21 1.87.87 2.33.66.07-.52.28-.87.51-1.07-1.78-.2-3.64-.89-3.64-3.95 0-.87.31-1.59.82-2.15-.08-.2-.36-1.02.08-2.12 0 0 .67-.21 2.2.82.64-.18 1.32-.27 2-.27s1.36.09 2 .27c1.53-1.04 2.2-.82 2.2-.82.44 1.1.16 1.92.08 2.12.51.56.82 1.27.82 2.15 0 3.07-1.87 3.75-3.65 3.95.29.25.54.73.54 1.48 0 1.07-.01 1.93-.01 2.2 0 .21.15.46.55.38A8.01 8.01 0 0 0 16 8c0-4.42-3.58-8-8-8Z"/>
                            </svg>
                            Authenticate with GitHub
                        </a>
                        <span style="color: var(--muted);">Χρειάζεται σύνδεση με GitHub για την αναζήτηση repositories.</span>
                    </div>
                @endif
            </div>

            <div class="card">
                <p class="card-title">Αναζήτηση repositories</p>

                <form class="search" id="search-form" onsubmit="return false;">
                    <input
                        type="search"
                        id="q"
                        name="q"
                        placeholder="Αναζήτηση repositories… (π.χ. laravel queue)"
                        autocomplete="off"
                        value="{{ request('q') }}"
                        @disabled(! $githubConnected)
                    >
                    <button type="submit" class="btn" @disabled(! $githubConnected)>Αναζήτηση</button>
                </form>

                @if ($githubConnected)
                    <p class="hint">Η αναζήτηση ξεκινά αυτόματα καθώς πληκτρολογείς.</p>
                @else
                    <p class="hint">Σύνδεσε πρώτα τον λογαριασμό σου στο GitHub.</p>
                @endif

                <p class="status" id="search-status"></p>
                <ul class="results" id="results"></ul>
            </div>
        </div>

        <script>
            const input    = document.getElementById('q');
            const results  = document.getElementById('results');
            const statusEl = document.getElementById('search-status');
            const endpoint = '/github/search';

            let timer = null;
            let controller = null;

            function setStatus(text, isError = false) {
                statusEl.textContent = text;
                statusEl.classList.toggle('error', isError);
            }

            function escapeHtml(value) {
                return String(value ?? '').replace(/[&<>"']/g, c => ({
                    '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;'
                })[c]);
            }

            function render(items) {
                results.innerHTML = items.map(repo => `
                    <li>
                        <a class="repo-name" href="${escapeHtml(repo.url)}" target="_blank" rel="noopener">
                            ${escapeHtml(repo.full_name)}
                        </a>
                        ${repo.private ? '<span class="meta" style="display:inline"> · private</span>' : ''}
                        <p class="repo-desc">${repo.description ? escapeHtml(repo.description) : '<em>Χωρίς περιγραφή</em>'}</p>
                        <div class="meta">
                            ${repo.language ? `<span>${escapeHtml(repo.language)}</span>` : ''}
                            <span>★ ${Number(repo.stars ?? 0).toLocaleString()}</span>
                            ${repo.updated_at ? `<span>updated ${escapeHtml(repo.updated_at.slice(0, 10))}</span>` : ''}
                        </div>
                    </li>
                `).join('');
            }

            async function search() {
                const q = input.value.trim();

                if (controller) controller.abort();

                if (q.length < 2) {
                    results.innerHTML = '';
                    setStatus('');
                    return;
                }

                controller = new AbortController();
                setStatus('Αναζήτηση…');

                const params = new URLSearchParams({ q });

                try {
                    const response = await fetch(`${endpoint}?${params}`, {
                        headers: { 'Accept': 'application/json' },
                        signal: controller.signal,
                    });
                    const data = await response.json();

                    if (!response.ok) {
                        results.innerHTML = '';
                        setStatus(data.message || 'Η αναζήτηση απέτυχε.', true);
                        return;
                    }

                    render(data.items);
                    setStatus(data.items.length ? `${data.total} αποτελέσματα` : 'Δεν βρέθηκαν repositories.');
                } catch (error) {
                    if (error.name === 'AbortError') return;
                    results.innerHTML = '';
                    setStatus('Σφάλμα δικτύου.', true);
                }
            }

            input.addEventListener('input', () => {
                clearTimeout(timer);
                timer = setTimeout(search, 350);
            });

            document.getElementById('search-form').addEventListener('submit', () => {
                clearTimeout(timer);
                search();
            });

            if (!input.disabled && input.value.trim()) search();
        </script>
    </body>
</html>
