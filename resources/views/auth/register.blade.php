@extends('layout.app')

@section('title', 'Create Account - TaskFlow')

@section('content')
<div class="min-h-[calc(100vh-16rem)] flex items-center justify-center py-8">
    <div class="w-full max-w-md">
        <!-- Card -->
        <div class="bg-white rounded-3xl p-8 sm:p-10 border border-slate-200/80 shadow-xl shadow-slate-200/40">
            <!-- Header -->
            <div class="text-center mb-8">
                <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-indigo-600 to-violet-500 text-white flex items-center justify-center mx-auto mb-4 shadow-lg shadow-indigo-600/30">
                    <svg class="w-6 h-6 stroke-[2.5]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zM4 19.235v-.11a6.375 6.375 0 0112.75 0v.109A12.375 12.375 0 014 19.235z" />
                    </svg>
                </div>
                <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Create Your Account</h1>
                <p class="text-sm text-slate-500 mt-1">Start organizing your life and projects today</p>
            </div>

            <!-- Register Form -->
            <form action="{{ route('register.store') }}" method="POST" id="register-form" class="space-y-4">
                @csrf

                <!-- Name -->
                <div>
                    <label for="name" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                        Username / Full Name
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
                               placeholder="Choose a username"
                               class="w-full pl-10 pr-4 py-3 rounded-xl border {{ $errors->has('name') ? 'border-rose-400 focus:ring-rose-400' : 'border-slate-200 focus:ring-indigo-500' }} text-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:border-transparent transition-all">
                    </div>
                    @error('name')
                        <p class="mt-1.5 text-xs text-rose-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                        Email Address
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <input type="email"
                               name="email"
                               id="email"
                               value="{{ old('email') }}"
                               required
                               placeholder="you@example.com"
                               class="w-full pl-10 pr-4 py-3 rounded-xl border {{ $errors->has('email') ? 'border-rose-400 focus:ring-rose-400' : 'border-slate-200 focus:ring-indigo-500' }} text-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:border-transparent transition-all">
                    </div>
                    @error('email')
                        <p class="mt-1.5 text-xs text-rose-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                        Password (Min. 8 characters)
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

                <!-- Confirm Password -->
                <div>
                    <label for="password_confirmation" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                        Confirm Password
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                        </div>
                        <input type="password"
                               name="password_confirmation"
                               id="password_confirmation"
                               required
                               placeholder="••••••••"
                               class="w-full pl-10 pr-4 py-3 rounded-xl border border-slate-200 text-sm text-slate-900 placeholder-slate-400 focus:ring-2 focus:ring-indigo-500 focus:outline-none focus:border-transparent transition-all">
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="pt-2">
                    <button type="submit"
                            id="register-btn"
                            class="w-full py-3.5 px-4 rounded-xl text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-700 active:scale-[0.99] shadow-lg shadow-indigo-600/30 transition-all flex items-center justify-center gap-2">
                        <span>Create Account</span>
                    </button>
                </div>
            </form>

            <!-- Bottom Link -->
            <div class="mt-8 pt-6 border-t border-slate-100 text-center">
                <p class="text-sm text-slate-500">
                    Already have an account?
                    <a href="{{ route('login') }}" class="font-semibold text-indigo-600 hover:text-indigo-700 hover:underline">
                        Sign in here
                    </a>
                </p>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    const form = document.getElementById('register-form');
    if (form) {
        form.addEventListener('submit', () => {
            const btn = document.getElementById('register-btn');
            if (btn) {
                btn.disabled = true;
                btn.classList.add('opacity-75', 'cursor-not-allowed');
                btn.innerHTML = `<svg class="animate-spin h-5 w-5 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg> <span>Creating Account...</span>`;
            }
        });
    }
</script>
@endpush
@endsection
