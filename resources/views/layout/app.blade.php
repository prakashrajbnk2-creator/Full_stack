<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-50">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">

    <title>@yield('title', 'TaskFlow - Modern Todo Application')</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Styles & Scripts via Vite -->
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <!-- Fallback Tailwind CSS CDN for instant rendering if assets build is unavailable -->
        <script src="https://cdn.tailwindcss.com"></script>
    @endif

    <style>
        body {
            font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
        }
        [x-cloak] { display: none !important; }
    </style>
</head>

<body class="h-full flex flex-col text-slate-800 antialiased selection:bg-indigo-500 selection:text-white">

    <!-- Navigation Header -->
    <header class="sticky top-0 z-40 bg-white/90 backdrop-blur-md border-b border-slate-200/80 transition-all duration-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Brand / Logo -->
                <div class="flex items-center gap-3">
                    <a href="{{ route('home') }}" class="flex items-center gap-2.5 group">
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-indigo-600 via-indigo-500 to-violet-500 flex items-center justify-center text-white shadow-md shadow-indigo-500/20 group-hover:scale-105 transition-transform duration-200">
                            <svg class="w-5 h-5 stroke-[2.5]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div>
                            <span class="text-xl font-bold bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-800 bg-clip-text text-transparent">TaskFlow</span>
                            <span class="hidden sm:inline-block text-[11px] font-semibold uppercase tracking-wider text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded-full ml-1">Pro</span>
                        </div>
                    </a>

                    @auth
                        <nav class="hidden md:flex items-center gap-1 ml-6 pl-6 border-l border-slate-200">
                            <a href="{{ route('dashboard') }}"
                               class="px-3.5 py-2 rounded-lg text-sm font-semibold transition-colors duration-150 {{ request()->routeIs('dashboard', 'tasks.*') ? 'bg-indigo-50 text-indigo-700' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
                                Dashboard
                            </a>
                        </nav>
                    @endauth
                </div>

                <!-- Right Action / User Profile -->
                <div class="flex items-center gap-3">
                    @auth
                        <div class="flex items-center gap-3">
                            <!-- User info pill -->
                            <div class="hidden sm:flex flex-col text-right">
                                <span class="text-sm font-bold text-slate-800 leading-tight">{{ Auth::user()->name }}</span>
                                <span class="text-xs text-slate-500 truncate max-w-[160px]">{{ Auth::user()->email }}</span>
                            </div>

                            <!-- Avatar -->
                            <div class="w-9 h-9 rounded-full bg-gradient-to-tr from-indigo-500 to-purple-600 text-white font-bold text-sm flex items-center justify-center shadow-sm">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </div>

                            <!-- Logout Button -->
                            <form method="POST" action="{{ route('logout') }}" class="inline">
                                @csrf
                                <button type="submit"
                                        title="Sign out"
                                        class="p-2 text-slate-500 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-colors duration-150 flex items-center gap-1.5 text-sm font-medium">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75" />
                                    </svg>
                                    <span class="hidden md:inline">Logout</span>
                                </button>
                            </form>
                        </div>
                    @else
                        <div class="flex items-center gap-2">
                            <a href="{{ route('login') }}" class="px-4 py-2 text-sm font-medium text-slate-700 hover:text-slate-900 hover:bg-slate-100 rounded-lg transition-colors">
                                Sign In
                            </a>
                            <a href="{{ route('register') }}" class="px-4 py-2 text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-700 rounded-lg shadow-sm shadow-indigo-600/30 transition-all duration-150">
                                Get Started
                            </a>
                        </div>
                    @endauth
                </div>
            </div>
        </div>
    </header>

    <!-- Global Toast / Flash Notifications -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full pt-4">
        @if (session('success'))
            <div id="flash-success" class="flex items-center justify-between p-4 mb-4 text-emerald-800 bg-emerald-50 border border-emerald-200 rounded-xl shadow-sm animate-fade-in transition-opacity duration-300">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-emerald-100 text-emerald-600 flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                    <div>
                        <p class="font-medium text-sm">{{ session('success') }}</p>
                    </div>
                </div>
                <button type="button" onclick="document.getElementById('flash-success').remove()" class="text-emerald-500 hover:text-emerald-800 p-1 rounded-lg">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        @endif

        @if (session('error'))
            <div id="flash-error" class="flex items-center justify-between p-4 mb-4 text-rose-800 bg-rose-50 border border-rose-200 rounded-xl shadow-sm animate-fade-in transition-opacity duration-300">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-rose-100 text-rose-600 flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                        </svg>
                    </div>
                    <div>
                        <p class="font-medium text-sm">{{ session('error') }}</p>
                    </div>
                </div>
                <button type="button" onclick="document.getElementById('flash-error').remove()" class="text-rose-500 hover:text-rose-800 p-1 rounded-lg">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        @endif

        @if ($errors->any())
            <div id="flash-validation" class="p-4 mb-4 text-rose-800 bg-rose-50 border border-rose-200 rounded-xl shadow-sm">
                <div class="flex items-start gap-3">
                    <div class="w-8 h-8 rounded-lg bg-rose-100 text-rose-600 flex items-center justify-center shrink-0 mt-0.5">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126z" />
                        </svg>
                    </div>
                    <div class="flex-1">
                        <p class="font-semibold text-sm">Please resolve the following issues:</p>
                        <ul class="mt-1 list-disc list-inside text-xs space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    <button type="button" onclick="document.getElementById('flash-validation').remove()" class="text-rose-500 hover:text-rose-800 p-1 rounded-lg">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        @endif
    </div>

    <!-- Main Content Area -->
    <main class="flex-1 max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-6">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="mt-auto border-t border-slate-200 bg-white py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-slate-500">
            <div class="flex items-center gap-2">
                <span class="font-semibold text-slate-700">TaskFlow</span>
                <span>&bull;</span>
                <span>Clean &amp; Productive Task Management</span>
            </div>
            <div>
                &copy; {{ date('Y') }} TaskFlow Application. All rights reserved.
            </div>
        </div>
    </footer>

    <!-- Universal Toast Auto-dismiss -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const successAlert = document.getElementById('flash-success');
            if (successAlert) {
                setTimeout(() => {
                    successAlert.style.opacity = '0';
                    setTimeout(() => successAlert.remove(), 300);
                }, 4000);
            }
        });
    </script>

    @stack('scripts')
</body>
</html>
