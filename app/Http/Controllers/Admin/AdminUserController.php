<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AdminUserController extends Controller
{
    /**
     * Display a listing of users with search and role filters.
     */
    public function index(Request $request): View
    {
        $query = User::query()->withCount('quizAttempts');

        if ($request->filled('search')) {
            $search = trim($request->input('search'));
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('school', 'like', "%{$search}%")
                    ->orWhere('nis', 'like', "%{$search}%")
                    ->orWhere('kelas', 'like', "%{$search}%");
            });
        }

        if ($request->filled('role')) {
            $role = $request->input('role');
            if (in_array($role, ['siswa', 'guru', 'admin'], true)) {
                $query->where('role', $role);
            }
        }

        $users = $query->latest('id')->paginate(12)->withQueryString();

        $roleCounts = [
            'all' => User::count(),
            'siswa' => User::where('role', 'siswa')->count(),
            'guru' => User::where('role', 'guru')->count(),
            'admin' => User::where('role', 'admin')->count(),
        ];

        return view('admin.users.index', [
            'users' => $users,
            'roleCounts' => $roleCounts,
            'currentSearch' => $request->input('search', ''),
            'currentRole' => $request->input('role', ''),
        ]);
    }

    /**
     * Show the form for creating a new user.
     */
    public function create(): View
    {
        return view('admin.users.create');
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
            'role' => ['required', 'string', Rule::in(['siswa', 'guru', 'admin'])],
            'school' => ['nullable', 'string', 'max:255'],
            'nis' => ['nullable', 'string', 'max:50'],
            'kelas' => ['nullable', 'string', 'max:100'],
            'points' => ['nullable', 'integer', 'min:0'],
        ], [
            'name.required' => 'Nama lengkap wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.unique' => 'Email ini sudah terdaftar di sistem.',
            'password.required' => 'Kata sandi wajib diisi.',
            'password.min' => 'Kata sandi minimal 8 karakter.',
            'role.required' => 'Pilih peran pengguna.',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'school' => $validated['school'] ?? null,
            'nis' => $validated['nis'] ?? null,
            'kelas' => $validated['kelas'] ?? null,
            'points' => $validated['points'] ?? 0,
        ]);

        ActivityLog::record(
            'user_created',
            'Admin '.Auth::user()->name.' membuat akun baru: '.$user->name.' ('.$user->role_label.').',
            Auth::user(),
            ['created_user_id' => $user->id, 'role' => $user->role]
        );

        return redirect()->route('admin.users.index')
            ->with('success', 'User '.$user->name.' berhasil ditambahkan!');
    }

    /**
     * Display the specified user profile & learning details.
     */
    public function show(User $user): View
    {
        $quizAttempts = $user->quizAttempts()
            ->latest('id')
            ->get();

        $activityLogs = $user->activityLogs()
            ->latest('id')
            ->limit(20)
            ->get();

        return view('admin.users.show', [
            'user' => $user,
            'quizAttempts' => $quizAttempts,
            'activityLogs' => $activityLogs,
        ]);
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user): View
    {
        return view('admin.users.edit', [
            'user' => $user,
        ]);
    }

    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'password' => ['nullable', 'string', 'min:8'],
            'role' => ['required', 'string', Rule::in(['siswa', 'guru', 'admin'])],
            'school' => ['nullable', 'string', 'max:255'],
            'nis' => ['nullable', 'string', 'max:50'],
            'kelas' => ['nullable', 'string', 'max:100'],
            'points' => ['nullable', 'integer', 'min:0'],
            'skor_praktik' => ['nullable', 'integer', 'min:0', 'max:100'],
        ], [
            'name.required' => 'Nama lengkap wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.unique' => 'Email ini sudah digunakan oleh user lain.',
            'password.min' => 'Kata sandi minimal 8 karakter.',
            'role.required' => 'Pilih peran pengguna.',
        ]);

        $updateData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role' => $validated['role'],
            'school' => $validated['school'] ?? null,
            'nis' => $validated['nis'] ?? null,
            'kelas' => $validated['kelas'] ?? null,
            'points' => $validated['points'] ?? $user->points,
            'skor_praktik' => array_key_exists('skor_praktik', $validated) ? $validated['skor_praktik'] : $user->skor_praktik,
        ];

        if (! empty($validated['password'])) {
            $updateData['password'] = Hash::make($validated['password']);
        }

        $user->update($updateData);

        ActivityLog::record(
            'user_updated',
            'Admin '.Auth::user()->name.' memperbarui akun '.$user->name.'.',
            Auth::user(),
            ['target_user_id' => $user->id, 'new_role' => $user->role]
        );

        return redirect()->route('admin.users.index')
            ->with('success', 'Data user '.$user->name.' berhasil diperbarui!');
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy(User $user): RedirectResponse
    {
        if ($user->id === Auth::id()) {
            return back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri saat sedang masuk.');
        }

        $userName = $user->name;
        $userRole = $user->role_label;

        $user->delete();

        ActivityLog::record(
            'user_deleted',
            'Admin '.Auth::user()->name.' menghapus akun: '.$userName.' ('.$userRole.').',
            Auth::user(),
            ['deleted_user_name' => $userName]
        );

        return redirect()->route('admin.users.index')
            ->with('success', 'User '.$userName.' berhasil dihapus.');
    }
}
