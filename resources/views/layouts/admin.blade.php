<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') — DAI Admin</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        navy: { DEFAULT: '#0b3168', light: 'rgba(255,255,255,0.12)', hover: 'rgba(255,255,255,0.07)' },
                    },
                    fontFamily: {
                        display: ['Manrope', 'sans-serif'],
                        body:    ['Inter', 'sans-serif'],
                        sans:    ['Inter', 'sans-serif'],
                    },
                }
            }
        }
    </script>

    <style>
        *, *::before, *::after { box-sizing: border-box; }

        html { font-size: 16px; }
        body {
            font-family: 'Inter', ui-sans-serif, system-ui, sans-serif !important;
            font-size: 1rem;
            line-height: 1.8;
            color: #0d1f3c;            /* hsl(215 70% 12%) — navy-tinted, matches design system */
            background: #f8f9fa;       /* hsl(210 17% 98%) — base surface layer */
        }

        h1, h2, h3, h4, h5, h6 {
            font-family: 'Manrope', ui-sans-serif, system-ui, sans-serif !important;
            font-weight: 700;
            line-height: 1.2;
            letter-spacing: -0.02em;   /* editorial tightness from design system */
        }

        /* ── Tailwind font-size overrides (accessibility-first, matches public site) ── */
        .text-xs   { font-size: 1rem !important;      line-height: 1.5rem !important; }   /* 16px / 24px */
        .text-sm   { font-size: 1.0625rem !important; line-height: 1.5625rem !important; } /* 17px / 25px */
        .text-base { font-size: 1.0625rem !important; line-height: 1.5625rem !important; } /* 17px / 25px */

        /* ── Border radius — design system uses 0.35rem everywhere ── */
        .rounded, .rounded-sm { border-radius: 0.25rem; }
        .rounded-md  { border-radius: 0.35rem; }
        .rounded-lg  { border-radius: 0.35rem; }
        .rounded-xl  { border-radius: 0.35rem; }
        .rounded-2xl { border-radius: 0.35rem; }
        /* .rounded-full intentionally NOT overridden — keeps circles for avatars etc. */

        /* ── Muted text — navy-tinted (matches design system muted-foreground) ── */
        .text-gray-500, .text-gray-400 { color: hsl(215, 19%, 45%) !important; }
        .text-gray-600 { color: hsl(215, 19%, 36%) !important; }

        /* ── Sidebar section headers ── */
        .nav-section {
            font-size: 0.7rem;
            font-weight: 700;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            color: rgba(255,255,255,0.35);
            padding: 14px 14px 5px;
            display: block;
        }

        /* ── Sidebar nav links ── */
        .nav-link {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 9px 14px;
            border-radius: 0.35rem;
            font-size: 1rem;           /* 16px — matches text-sm minimum */
            font-weight: 500;
            color: rgba(255,255,255,0.72);
            transition: background 0.15s, color 0.15s;
            text-decoration: none;
            width: 100%;
        }
        .nav-link:hover { background: rgba(255,255,255,0.07); color: #fff; }
        .nav-link.active { background: rgba(255,255,255,0.12); color: #fff; }

        /* ── Sidebar sub-links ── */
        .sub-link {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 7px 14px 7px 42px;
            border-radius: 0.35rem;
            font-size: 1rem;           /* was 14.4px — raised to 16px */
            font-weight: 400;
            color: rgba(255,255,255,0.58);
            transition: background 0.15s, color 0.15s;
            text-decoration: none;
        }
        .sub-link:hover { background: rgba(255,255,255,0.07); color: #fff; }
        .sub-link.active { color: #fff; font-weight: 500; }

        /* ── Sidebar footer ── */
        .sidebar-footer-label {
            font-size: 0.9rem;
            color: rgba(255,255,255,0.38);
            margin-bottom: 2px;
        }
        .sidebar-footer-user {
            font-size: 1rem;
            font-weight: 700;
            color: #fff;
            margin-bottom: 12px;
        }
        .sidebar-logout {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 8px 12px;
            border-radius: 0.35rem;
            font-size: 1rem;
            font-weight: 500;
            color: rgba(255,255,255,0.52);
            transition: background 0.15s, color 0.15s;
            width: 100%;
            background: none;
            border: none;
            cursor: pointer;
            text-align: left;
        }
        .sidebar-logout:hover { background: rgba(255,255,255,0.07); color: #fff; }

        /* ── Sidebar logo ── */
        .sidebar-logo-dai {
            font-family: 'Manrope', ui-sans-serif, system-ui, sans-serif !important;
            font-size: 1.5rem;
            font-weight: 800;
            color: #fff;
            letter-spacing: -0.02em;
        }
        .sidebar-logo-admin {
            font-family: 'Inter', ui-sans-serif, system-ui, sans-serif !important;
            font-size: 1rem;
            font-weight: 500;
            color: rgba(255,255,255,0.55);
        }

        /* ── Page header title ── */
        .page-header-title {
            font-family: 'Manrope', ui-sans-serif, system-ui, sans-serif !important;
            font-size: 1.3125rem;      /* 21px — Manrope at display weight */
            font-weight: 700;
            letter-spacing: -0.02em;
            color: #0d1f3c;
        }

        /* ── Main content typography ── */
        main {
            font-size: 1.0625rem;      /* 17px */
        }
        main p, main li, main span, main td {
            font-size: 1.0625rem;      /* 17px */
            line-height: 1.5625rem;
        }
        main label {
            font-size: 1.0625rem;
            font-weight: 500;
            color: #1e3a5f;
        }
        main input, main textarea, main select {
            font-size: 1.0625rem;
            font-family: 'Inter', ui-sans-serif, system-ui, sans-serif !important;
        }
        main h2, main h3 {
            font-family: 'Manrope', ui-sans-serif, system-ui, sans-serif !important;
            font-weight: 700;
            letter-spacing: -0.02em;
        }
        main table {
            font-size: 1.0625rem;
            width: 100%;
        }
        main th {
            font-weight: 600;
            font-size: 0.875rem;       /* 14px — table headers can be slightly smaller */
            text-transform: uppercase;
            letter-spacing: 0.04em;
            color: hsl(215, 19%, 45%); /* navy-tinted muted */
        }

        /* ── Elevation / shadows (navy-tinted, matches design system) ── */
        .shadow, .shadow-sm {
            box-shadow: 0px 4px 12px 0px rgba(25, 28, 29, 0.06) !important;
        }
        .shadow-md {
            box-shadow: 0px 8px 20px -2px rgba(25, 28, 29, 0.06), 0px 4px 6px -4px rgba(25, 28, 29, 0.04) !important;
        }

        /* ── Flash messages ── */
        .flash-success { background:#f0fdf4; border:1px solid #86efac; color:#166534; padding:13px 18px; border-radius:0.35rem; margin-bottom:20px; font-size:1.0625rem; line-height:1.5625rem; }
        .flash-error   { background:#fef2f2; border:1px solid #fca5a5; color:#991b1b; padding:13px 18px; border-radius:0.35rem; margin-bottom:20px; font-size:1.0625rem; line-height:1.5625rem; }

        /* ── Header action button (always visible in top bar) ── */
        .header-btn {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 9px 20px;
            background: #0b3168;
            color: #fff;
            border: none;
            border-radius: 0.35rem;
            font-size: 1rem;           /* 16px — matches text-xs floor */
            font-weight: 700;
            cursor: pointer;
            text-decoration: none;
            white-space: nowrap;
            font-family: 'Inter', sans-serif;
            line-height: 1.4;
        }
        .header-btn:hover { background: hsl(215,81%,31%); color: #fff; }
        .header-btn-outline {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 9px 20px;
            background: #fff;
            color: #0b3168;
            border: 1.5px solid #0b3168;
            border-radius: 0.35rem;
            font-size: 1rem;
            font-weight: 700;
            cursor: pointer;
            text-decoration: none;
            white-space: nowrap;
            font-family: 'Inter', sans-serif;
            line-height: 1.4;
        }
        .header-btn-outline:hover { background: #f0f4ff; }
    </style>
</head>
<body style="height:100vh;overflow:hidden">
<div style="display:flex;height:100vh;overflow:hidden">

    {{-- ── Sidebar ── --}}
    <aside class="w-64 flex flex-col flex-shrink-0" style="background:hsl(215,81%,14%);overflow:hidden">

        {{-- Logo --}}
        <div class="px-5 py-5" style="border-bottom:1px solid rgba(255,255,255,0.08)">
            <div class="flex items-center gap-2.5">
                <span class="sidebar-logo-dai">DAI</span>
                <span class="sidebar-logo-admin">Admin</span>
            </div>
        </div>

        {{-- Navigation --}}
        <nav class="flex-1 px-3 py-3 overflow-y-auto" style="display:flex;flex-direction:column;gap:2px">

            {{-- OVERVIEW --}}
            <span class="nav-section">Overview</span>
            <a href="{{ route('admin.dashboard') }}"
               class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <svg class="w-4 h-4 shrink-0" style="opacity:0.65" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                        d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                </svg>
                Dashboard
            </a>

            {{-- SUBMISSIONS --}}
            <span class="nav-section">Submissions</span>
            <a href="{{ route('admin.payments.index') }}"
               class="nav-link {{ request()->routeIs('admin.payments.*') ? 'active' : '' }}">
                <svg class="w-4 h-4 shrink-0" style="opacity:0.65" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                        d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                </svg>
                Payments
            </a>
            <a href="{{ route('admin.applications.index') }}"
               class="nav-link {{ request()->routeIs('admin.applications.*') ? 'active' : '' }}">
                <svg class="w-4 h-4 shrink-0" style="opacity:0.65" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
                Applications
            </a>
            <a href="{{ route('admin.hcp-referrals.index') }}"
               class="nav-link {{ request()->routeIs('admin.hcp-referrals.*') ? 'active' : '' }}">
                <svg class="w-4 h-4 shrink-0" style="opacity:0.65" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
                HCP Referrals
            </a>
            <a href="{{ route('admin.contact-submissions.index') }}"
               class="nav-link {{ request()->routeIs('admin.contact-submissions.*') ? 'active' : '' }}">
                <svg class="w-4 h-4 shrink-0" style="opacity:0.65" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                        d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
                Contact Messages
            </a>

            {{-- EMAIL --}}
            <span class="nav-section">Email</span>
            <a href="{{ route('admin.email-templates.index') }}"
               class="nav-link {{ request()->routeIs('admin.email-templates.*') ? 'active' : '' }}">
                <svg class="w-4 h-4 shrink-0" style="opacity:0.65" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <rect x="2" y="4" width="20" height="16" rx="2" stroke-width="1.75"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M2 7l10 7 10-7"/>
                </svg>
                Email Templates
            </a>
            <a href="{{ route('admin.email-logs.index') }}"
               class="nav-link {{ request()->routeIs('admin.email-logs.*') ? 'active' : '' }}">
                <svg class="w-4 h-4 shrink-0" style="opacity:0.65" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                </svg>
                Email Logs
            </a>

            {{-- CONTENT MANAGER --}}
            <span class="nav-section">Content Manager</span>

            @php
                $contentActive = request()->routeIs('admin.cms-pages.*')
                    || request()->routeIs('admin.services.*')
                    || request()->routeIs('admin.team-members.*')
                    || request()->routeIs('admin.testimonials.*');
            @endphp

            <button class="nav-link {{ $contentActive ? 'active' : '' }}" id="content-toggle" type="button">
                <svg class="w-4 h-4 shrink-0" style="opacity:0.65" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                        d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
                </svg>
                <span style="flex:1;text-align:left">Content</span>
                <svg class="w-3.5 h-3.5" style="opacity:0.45;transition:transform 0.2s;{{ $contentActive ? 'transform:rotate(90deg)' : '' }}" id="content-chevron" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </button>

            <div id="content-submenu" style="display:{{ $contentActive ? 'flex' : 'none' }};flex-direction:column;gap:2px">
                <a href="{{ route('admin.cms-pages.index') }}?type=page"
                   class="sub-link {{ request()->routeIs('admin.cms-pages.*') && request()->get('type') !== 'service' ? 'active' : '' }}">
                    <svg class="w-3.5 h-3.5" style="opacity:0.55;flex-shrink:0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Pages
                </a>
                <a href="{{ route('admin.services.index') }}"
                   class="sub-link {{ request()->routeIs('admin.services.*') ? 'active' : '' }}">
                    <svg class="w-3.5 h-3.5" style="opacity:0.55;flex-shrink:0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M4.5 12.75l6 6 9-13.5"/>
                    </svg>
                    Services
                </a>
                <a href="{{ route('admin.team-members.index') }}"
                   class="sub-link {{ request()->routeIs('admin.team-members.*') ? 'active' : '' }}">
                    <svg class="w-3.5 h-3.5" style="opacity:0.55;flex-shrink:0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    Team Members
                </a>
                <a href="{{ route('admin.testimonials.index') }}"
                   class="sub-link {{ request()->routeIs('admin.testimonials.*') ? 'active' : '' }}">
                    <svg class="w-3.5 h-3.5" style="opacity:0.55;flex-shrink:0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                            d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-3 3-3-3z"/>
                    </svg>
                    Testimonials
                </a>
            </div>

            {{-- SETTINGS --}}
            <span class="nav-section">Settings</span>
            <a href="{{ route('admin.admin-users.index') }}"
               class="nav-link {{ request()->routeIs('admin.admin-users.*') ? 'active' : '' }}">
                <svg class="w-4 h-4 shrink-0" style="opacity:0.65" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                        d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                </svg>
                Admin Users
            </a>
            <a href="{{ route('admin.reports.index') }}"
               class="nav-link {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}">
                <svg class="w-4 h-4 shrink-0" style="opacity:0.65" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                        d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                Reports
            </a>
            <a href="{{ route('admin.settings.index') }}"
               class="nav-link {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
                <svg class="w-4 h-4 shrink-0" style="opacity:0.65" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                        d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/>
                </svg>
                Settings
            </a>
            <a href="{{ route('home') }}" target="_blank" class="nav-link">
                <svg class="w-4 h-4 shrink-0" style="opacity:0.65" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                        d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                </svg>
                View Website
            </a>

        </nav>

        {{-- Bottom — user + logout --}}
        <div class="px-4 py-4" style="border-top:1px solid rgba(255,255,255,0.08)">
            <p class="sidebar-footer-label">Logged in as</p>
            <p class="sidebar-footer-user">{{ session('admin_username', 'admin') }}</p>
            <form method="POST" action="{{ route('admin.logout') }}">
                @csrf
                <button type="submit" class="sidebar-logout">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                    Logout
                </button>
            </form>
        </div>
    </aside>

    {{-- ── Main content ── --}}
    <div class="flex-1 flex flex-col" style="min-width:0;overflow:hidden">
        <header style="background:#fff;border-bottom:1px solid #e5e7eb;padding:14px 24px;display:flex;align-items:center;justify-content:space-between;gap:16px;flex-shrink:0">
            <h1 class="page-header-title">@yield('page-title', 'Dashboard')</h1>
            <div style="display:flex;align-items:center;gap:10px;flex-shrink:0">@yield('page-actions')</div>
        </header>
        <main style="flex:1;padding:24px;overflow-y:auto">
            @if(session('success'))
            <div class="flash-success">{{ session('success') }}</div>
            @endif
            @if(session('error'))
            <div class="flash-error">{{ session('error') }}</div>
            @endif
            @yield('content')
        </main>
    </div>
</div>

<script>
    const toggle = document.getElementById('content-toggle');
    const submenu = document.getElementById('content-submenu');
    const chevron = document.getElementById('content-chevron');
    if (toggle && submenu) {
        toggle.addEventListener('click', function () {
            const open = submenu.style.display !== 'none';
            submenu.style.display = open ? 'none' : 'flex';
            if (chevron) chevron.style.transform = open ? '' : 'rotate(90deg)';
        });
        // Ensure initial state is correct
        @if($contentActive)
        submenu.style.display = 'flex';
        @else
        submenu.style.display = 'none';
        @endif
    }
</script>

@stack('scripts')
</body>
</html>
