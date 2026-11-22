@extends('layout.app')

@section('title', 'TaskFlow - Modern Todo Application')

@section('content')
<div class="py-12 sm:py-20 flex flex-col items-center text-center">
    <!-- Top badge -->
    <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-indigo-50 border border-indigo-100 text-indigo-700 text-xs font-semibold mb-8 animate-fade-in">
        <span class="flex h-2 w-2 rounded-full bg-indigo-600"></span>
        Simple. Powerful. Productive.
    </div>

    <!-- Hero Title -->
    <h1 class="text-4xl sm:text-6xl font-black text-slate-900 tracking-tight max-w-3xl leading-[1.15]">
        Master your workflow with <span class="bg-gradient-to-r from-indigo-600 via-indigo-500 to-purple-600 bg-clip-text text-transparent">TaskFlow</span>
    </h1>

    <!-- Subtitle -->
    <p class="text-lg sm:text-xl text-slate-600 max-w-2xl mt-6 leading-relaxed">
        Stay organized, track your goals in real time, and eliminate procrastination with an intuitive, distraction-free task management experience.
    </p>

    <!-- CTAs -->
    <div class="flex flex-col sm:flex-row items-center gap-4 mt-10">
        @auth
            <a href="{{ route('dashboard') }}"
               class="px-8 py-4 rounded-2xl text-base font-bold text-white bg-indigo-600 hover:bg-indigo-700 active:scale-[0.98] shadow-xl shadow-indigo-600/30 transition-all flex items-center gap-2">
                <span>Go to Your Dashboard</span>
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                </svg>
            </a>
        @else
            <a href="{{ route('register') }}"
               class="px-8 py-4 rounded-2xl text-base font-bold text-white bg-indigo-600 hover:bg-indigo-700 active:scale-[0.98] shadow-xl shadow-indigo-600/30 transition-all flex items-center gap-2">
                <span>Get Started Free</span>
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                </svg>
            </a>
            <a href="{{ route('login') }}"
               class="px-8 py-4 rounded-2xl text-base font-bold text-slate-700 bg-white hover:bg-slate-50 border border-slate-200 active:scale-[0.98] shadow-sm transition-all">
                Sign In
            </a>
        @endauth
    </div>

    <!-- Feature Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 sm:gap-8 max-w-5xl mt-20 text-left">
        <!-- Feature 1 -->
        <div class="bg-white p-7 rounded-3xl border border-slate-200/80 shadow-sm hover:shadow-md transition-shadow">
            <div class="w-12 h-12 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center mb-5">
                <svg class="w-6 h-6 stroke-[2]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <h3 class="text-lg font-bold text-slate-900">Effortless Tracking</h3>
            <p class="text-sm text-slate-500 mt-2 leading-relaxed">
                Create, update, and organize tasks with single-click completion toggles and instant visual indicators.
            </p>
        </div>

        <!-- Feature 2 -->
        <div class="bg-white p-7 rounded-3xl border border-slate-200/80 shadow-sm hover:shadow-md transition-shadow">
            <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center mb-5">
                <svg class="w-6 h-6 stroke-[2]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z" />
                </svg>
            </div>
            <h3 class="text-lg font-bold text-slate-900">Real-Time Statistics</h3>
            <p class="text-sm text-slate-500 mt-2 leading-relaxed">
                Stay motivated with live metrics tracking completed tasks, pending items, and your overall completion rate.
            </p>
        </div>

        <!-- Feature 3 -->
        <div class="bg-white p-7 rounded-3xl border border-slate-200/80 shadow-sm hover:shadow-md transition-shadow">
            <div class="w-12 h-12 rounded-2xl bg-purple-50 text-purple-600 flex items-center justify-center mb-5">
                <svg class="w-6 h-6 stroke-[2]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                </svg>
            </div>
            <h3 class="text-lg font-bold text-slate-900">Smart Search &amp; Filters</h3>
            <p class="text-sm text-slate-500 mt-2 leading-relaxed">
                Find exactly what you need in seconds with powerful live keyword searching and status filter tabs.
            </p>
        </div>
    </div>
</div>
@endsection
