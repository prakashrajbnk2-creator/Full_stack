@extends('layout.app')

@section('title', 'Sign In - TaskFlow')

@section('content')
<div class="min-h-[calc(100vh-16rem)] flex items-center justify-center py-8">
    <div class="w-full max-w-md">
        <!-- Card -->
        <div class="bg-white rounded-3xl p-8 sm:p-10 border border-slate-200/80 shadow-xl shadow-slate-200/40">
            <!-- Header -->
            <div class="text-center mb-8">
                <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-indigo-600 to-violet-500 text-white flex items-center justify-center mx-auto mb-4 shadow-lg shadow-indigo-600/30">
                    <svg class="w-6 h-6 stroke-[2.5]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                    </svg>
                </div>
                <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Welcome Back</h1>
                <p class="text-sm text-slate-500 mt-1">Sign in to your account to manage your tasks</p>
            </div>

            <!-- Login Form -->
            <form action="{{ route('login.store') }}" method="POST" id="login-form" class="space-y-5">
                @csrf

                <!-- Name / Username -->
                <div>
                    <label for="name" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                        Username / Name
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        <input type="text"
                               name="name"
                               id="name"
                               value="{{ old('name') }}"
                               required
                               autofocus
                               placeholder="Enter your username"
                               class="w-full pl-10 pr-4 py-3 rounded-xl border {{ $errors->has('name') ? 'border-rose-400 focus:ring-rose-400' : 'border-slate-200 focus:ring-indigo-500' }} text-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:border-transparent transition-all">
                    </div>
                    @error('name')
                        <p class="mt-1.5 text-xs text-rose-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                        Password
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                        </div>
                        <input type="password"
                               name="password"
                               id="password"
                               required
                               placeholder="••••••••"
                               class="w-full pl-10 pr-4 py-3 rounded-xl border {{ $errors->has('password') ? 'border-rose-400 focus:ring-rose-400' : 'border-slate-200 focus:ring-indigo-500' }} text-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:border-transparent transition-all">
                    </div>
                    @error('password')
                        <p class="mt-1.5 text-xs text-rose-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Remember Me Checkbox -->
                <div class="flex items-center justify-between">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox"
                               name="remember"
                               value="1"
                               class="w-4 h-4 rounded text-indigo-600 focus:ring-indigo-500 border-slate-300">
                        <span class="text-xs font-medium text-slate-600">Remember this device</span>
                    </label>
                </div>

                <!-- Submit Button -->
                <button type="submit"
                        id="login-btn"
                        class="w-full py-3.5 px-4 rounded-xl text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-700 active:scale-[0.99] shadow-lg shadow-indigo-600/30 transition-all flex items-center justify-center gap-2">
                    <span>Sign In</span>
                </button>
            </form>

            <!-- Bottom Link -->
            <div class="mt-8 pt-6 border-t border-slate-100 text-center">
                <p class="text-sm text-slate-500">
                    Don't have an account yet?
                    <a href="{{ route('register') }}" class="font-semibold text-indigo-600 hover:text-indigo-700 hover:underline">
                        Create an account
                    </a>
                </p>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    const form = document.getElementById('login-form');
    if (form) {
        form.addEventListener('submit', () => {
            const btn = document.getElementById('login-btn');
            if (btn) {
                btn.disabled = true;
                btn.classList.add('opacity-75', 'cursor-not-allowed');
                btn.innerHTML = `<svg class="animate-spin h-5 w-5 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg> <span>Signing in...</span>`;
            }
        });
    }
</script>
@endpush
@endsection
