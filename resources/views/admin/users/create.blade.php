@extends('admin.layout')

@section('title', 'Tambah Pengguna Baru')
@section('page-title', 'Tambah Pengguna Baru')
@section('page-subtitle', 'Buat akun siswa, guru, atau administrator baru untuk Flowchart Lab.')

@section('header-actions')
    <a href="{{ route('admin.users.index') }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-full border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 font-bold text-xs transition">
        &larr; Kembali ke Daftar
    </a>
@endsection

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-2xs">
        <form action="{{ route('admin.users.store') }}" method="POST" class="space-y-5">
            @csrf

            <!-- Name -->
            <div>
                <label for="name" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                    Nama Lengkap <span class="text-rose-500">*</span>
                </label>
                <input type="text"
                       name="name"
                       id="name"
                       value="{{ old('name') }}"
                       required
                       placeholder="Contoh: Muhammad Rizky"
                       class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-amber-400 focus:outline-none">
            </div>

            <!-- Email -->
            <div>
                <label for="email" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                    Alamat Email <span class="text-rose-500">*</span>
                </label>
                <input type="email"
                       name="email"
                       id="email"
                       value="{{ old('email') }}"
                       required
                       placeholder="nama@smk.sch.id"
                       class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-amber-400 focus:outline-none">
            </div>

            <!-- Password -->
            <div>
                <label for="password" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                    Kata Sandi <span class="text-rose-500">*</span>
                </label>
                <input type="password"
                       name="password"
                       id="password"
                       required
                       minlength="8"
                       placeholder="Minimal 8 karakter"
                       class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-amber-400 focus:outline-none">
            </div>

            <!-- Role Selection -->
            <div>
                <label for="role" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                    Peran (Role) Pengguna <span class="text-rose-500">*</span>
                </label>
                <select name="role" id="role" required class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-amber-400 focus:outline-none bg-white">
                    <option value="siswa" {{ old('role') === 'siswa' ? 'selected' : '' }}>Siswa (Peserta Didik SMK)</option>
                    <option value="guru" {{ old('role') === 'guru' ? 'selected' : '' }}>Guru SMK (Pendidik)</option>
                    <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Administrator (Akses Penuh)</option>
                </select>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2">
                <!-- School -->
                <div>
                    <label for="school" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                        Asal Sekolah
                    </label>
                    <input type="text"
                           name="school"
                           id="school"
                           value="{{ old('school') }}"
                           placeholder="Contoh: SMKN 1 Jakarta"
                           class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-amber-400 focus:outline-none">
                </div>

                <!-- NIS -->
                <div>
                    <label for="nis" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                        NIS / Nomor Induk
                    </label>
                    <input type="text"
                           name="nis"
                           id="nis"
                           value="{{ old('nis') }}"
                           placeholder="Contoh: 220914"
                           class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-amber-400 focus:outline-none">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <!-- Kelas -->
                <div>
                    <label for="kelas" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                        Kelas & Jurusan
                    </label>
                    <input type="text"
                           name="kelas"
                           id="kelas"
                           value="{{ old('kelas') }}"
                           placeholder="Contoh: X RPL 1"
                           class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-amber-400 focus:outline-none">
                </div>

                <!-- Points -->
                <div>
                    <label for="points" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                        Poin Awal
                    </label>
                    <input type="number"
                           name="points"
                           id="points"
                           value="{{ old('points', 0) }}"
                           min="0"
                           class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-amber-400 focus:outline-none">
                </div>
            </div>

            <!-- Submit Button -->
            <div class="pt-4 flex items-center justify-end gap-3 border-t border-slate-100">
                <a href="{{ route('admin.users.index') }}" class="px-5 py-2.5 rounded-full border border-slate-200 text-slate-600 hover:bg-slate-50 text-xs font-bold transition">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2.5 rounded-full bg-amber-500 hover:bg-amber-600 text-slate-950 text-xs font-extrabold shadow-sm transition cursor-pointer">
                    Simpan Pengguna
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

