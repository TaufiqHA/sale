@extends('layouts.administrator')

@section('title', 'Pengaturan Akun')

@section('content')
<div class="max-w-5xl mx-auto space-y-8 pb-12">
    <!-- Page Sub-Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-6 rounded-2xl border border-slate-200/80 shadow-xs">
        <div class="flex items-center gap-4">
            <div class="h-14 w-14 rounded-2xl bg-brand-50 border border-brand-100 flex items-center justify-center text-brand shrink-0">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A7.488 7.488 0 0012 15.75a7.488 7.488 0 00-5.982 2.975m11.963 0a9 9 0 10-11.963 0m11.963 0A8.966 8.966 0 0112 21a8.966 8.966 0 01-5.982-2.275M15 9.75a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
            </div>
            <div>
                <h2 class="text-xl font-bold text-slate-900">Pengaturan Akun</h2>
                <p class="text-sm text-slate-500 mt-0.5">Kelola data informasi profil dan kata sandi akun Anda.</p>
            </div>
        </div>
        <div class="flex items-center gap-2">
            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200/60">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                Akun Aktif
            </span>
        </div>
    </div>

    <!-- Flash Messages -->
    @if (session('success'))
        <div class="flex items-start gap-3 p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl shadow-xs" role="alert">
            <svg class="w-5 h-5 text-emerald-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <div class="text-sm font-medium">
                {{ session('success') }}
            </div>
        </div>
    @endif

    @if ($errors->any())
        <div class="p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-xl shadow-xs" role="alert">
            <div class="flex items-center gap-2 font-semibold text-sm mb-1">
                <svg class="w-5 h-5 text-rose-600 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
                Terdapat beberapa kesalahan:
            </div>
            <ul class="list-disc list-inside text-sm space-y-0.5 ps-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        <!-- Main Form Column: Profile Info -->
        <div class="lg:col-span-7 bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
            <div class="p-6 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-brand-50 rounded-lg text-brand">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-slate-900">Informasi Profil</h3>
                        <p class="text-xs text-slate-500">Perbarui informasi identitas pribadi Anda</p>
                    </div>
                </div>
            </div>

            <form action="{{ route('administrator.profile.update') }}" method="POST" class="p-6 space-y-5">
                @csrf
                @method('PUT')

                <!-- User Summary Card Header -->
                <div class="flex items-center gap-4 p-4 rounded-xl bg-slate-50 border border-slate-100 mb-6">
                    <div class="h-12 w-12 rounded-full bg-brand text-white flex items-center justify-center font-bold text-lg shadow-sm">
                        {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="text-base font-bold text-slate-800 truncate">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-slate-500 truncate mb-1">{{ auth()->user()->email }}</p>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-xs font-medium bg-brand-50 text-brand border border-brand-200/50 capitalize">
                            {{ auth()->user()->role }}
                        </span>
                    </div>
                </div>

                <!-- Field: Nama Lengkap -->
                <div>
                    <label for="name" class="block mb-2 text-sm font-semibold text-slate-700">Nama Lengkap <span class="text-rose-500">*</span></label>
                    <div class="relative">
                        <div class="absolute inset-y-0 start-0 flex items-center ps-3.5 pointer-events-none text-slate-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"></path>
                            </svg>
                        </div>
                        <input type="text" id="name" name="name" value="{{ old('name', auth()->user()->name) }}" required
                            class="block w-full p-3 ps-10 bg-slate-50 border border-slate-200 text-slate-800 text-sm rounded-xl focus:bg-white focus:ring-2 focus:ring-brand focus:border-brand transition duration-150 placeholder:text-slate-400"
                            placeholder="Masukkan nama lengkap Anda">
                    </div>
                    @error('name')
                        <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Field: Email -->
                <div>
                    <label for="email" class="block mb-2 text-sm font-semibold text-slate-700">Alamat Email <span class="text-rose-500">*</span></label>
                    <div class="relative">
                        <div class="absolute inset-y-0 start-0 flex items-center ps-3.5 pointer-events-none text-slate-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"></path>
                            </svg>
                        </div>
                        <input type="email" id="email" name="email" value="{{ old('email', auth()->user()->email) }}" required
                            class="block w-full p-3 ps-10 bg-slate-50 border border-slate-200 text-slate-800 text-sm rounded-xl focus:bg-white focus:ring-2 focus:ring-brand focus:border-brand transition duration-150 placeholder:text-slate-400"
                            placeholder="nama@domain.com">
                    </div>
                    @error('email')
                        <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Field: Role (Read-only) -->
                <div>
                    <label class="block mb-2 text-sm font-semibold text-slate-700">Peran / Role</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 start-0 flex items-center ps-3.5 pointer-events-none text-slate-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751A11.959 11.959 0 0112 2.714z"></path>
                            </svg>
                        </div>
                        <input type="text" value="{{ ucfirst(auth()->user()->role) }}" disabled readonly
                            class="block w-full p-3 ps-10 bg-slate-100 border border-slate-200 text-slate-500 text-sm rounded-xl cursor-not-allowed select-none">
                    </div>
                    <p class="mt-1 text-xs text-slate-400">Hak akses ditentukan oleh sistem dan tidak dapat diubah sendiri.</p>
                </div>

                <!-- Action Button -->
                <div class="pt-4 flex justify-end">
                    <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 bg-brand hover:bg-brand-hover active:scale-[0.99] text-white font-semibold text-sm rounded-xl transition duration-150 shadow-sm cursor-pointer">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"></path>
                        </svg>
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>

        <!-- Secondary Form Column: Security & Password -->
        <div class="lg:col-span-5 bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
            <div class="p-6 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-amber-50 rounded-lg text-amber-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-slate-900">Ubah Kata Sandi</h3>
                        <p class="text-xs text-slate-500">Perbarui sandi untuk menjaga keamanan akun</p>
                    </div>
                </div>
            </div>

            <form action="{{ route('administrator.profile.password') }}" method="POST" class="p-6 space-y-5">
                @csrf
                @method('PUT')

                <!-- Field: Current Password -->
                <div>
                    <label for="current_password" class="block mb-2 text-sm font-semibold text-slate-700">Kata Sandi Saat Ini <span class="text-rose-500">*</span></label>
                    <div class="relative">
                        <input type="password" id="current_password" name="current_password" required
                            class="block w-full p-3 pe-10 bg-slate-50 border border-slate-200 text-slate-800 text-sm rounded-xl focus:bg-white focus:ring-2 focus:ring-brand focus:border-brand transition duration-150 placeholder:text-slate-400"
                            placeholder="••••••••">
                        <button type="button" onclick="togglePasswordVisibility('current_password', this)" class="absolute inset-y-0 end-0 flex items-center pe-3 text-slate-400 hover:text-slate-600 cursor-pointer">
                            <svg class="w-5 h-5 eye-icon" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                        </button>
                    </div>
                    @error('current_password')
                        <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Field: New Password -->
                <div>
                    <label for="password" class="block mb-2 text-sm font-semibold text-slate-700">Kata Sandi Baru <span class="text-rose-500">*</span></label>
                    <div class="relative">
                        <input type="password" id="password" name="password" required
                            class="block w-full p-3 pe-10 bg-slate-50 border border-slate-200 text-slate-800 text-sm rounded-xl focus:bg-white focus:ring-2 focus:ring-brand focus:border-brand transition duration-150 placeholder:text-slate-400"
                            placeholder="Minimal 8 karakter">
                        <button type="button" onclick="togglePasswordVisibility('password', this)" class="absolute inset-y-0 end-0 flex items-center pe-3 text-slate-400 hover:text-slate-600 cursor-pointer">
                            <svg class="w-5 h-5 eye-icon" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                        </button>
                    </div>
                    @error('password')
                        <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Field: Password Confirmation -->
                <div>
                    <label for="password_confirmation" class="block mb-2 text-sm font-semibold text-slate-700">Konfirmasi Kata Sandi Baru <span class="text-rose-500">*</span></label>
                    <div class="relative">
                        <input type="password" id="password_confirmation" name="password_confirmation" required
                            class="block w-full p-3 pe-10 bg-slate-50 border border-slate-200 text-slate-800 text-sm rounded-xl focus:bg-white focus:ring-2 focus:ring-brand focus:border-brand transition duration-150 placeholder:text-slate-400"
                            placeholder="Ulangi kata sandi baru">
                        <button type="button" onclick="togglePasswordVisibility('password_confirmation', this)" class="absolute inset-y-0 end-0 flex items-center pe-3 text-slate-400 hover:text-slate-600 cursor-pointer">
                            <svg class="w-5 h-5 eye-icon" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Password Info -->
                <div class="p-3 bg-slate-50 rounded-xl border border-slate-100 text-xs text-slate-500 space-y-1">
                    <p class="font-medium text-slate-700">Persyaratan kata sandi:</p>
                    <ul class="list-disc list-inside space-y-0.5">
                        <li>Minimal terdiri dari 8 karakter</li>
                        <li>Pastikan konfirmasi kata sandi cocok</li>
                    </ul>
                </div>

                <!-- Action Button -->
                <div class="pt-2 flex justify-end">
                    <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 bg-slate-900 hover:bg-slate-800 active:scale-[0.99] text-white font-semibold text-sm rounded-xl transition duration-150 shadow-sm cursor-pointer">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"></path>
                        </svg>
                        Ubah Kata Sandi
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function togglePasswordVisibility(inputId, button) {
        const input = document.getElementById(inputId);
        if (!input) return;

        if (input.type === 'password') {
            input.type = 'text';
            button.innerHTML = `
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88"></path>
                </svg>
            `;
        } else {
            input.type = 'password';
            button.innerHTML = `
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
            `;
        }
    }
</script>
@endsection
