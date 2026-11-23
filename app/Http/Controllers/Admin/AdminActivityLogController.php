<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminActivityLogController extends Controller
{
    /**
     * Display a listing of activity logs with action and search filters.
     */
    public function index(Request $request): View
    {
        $query = ActivityLog::with('user');

        if ($request->filled('action')) {
            $query->where('action', $request->input('action'));
        }

        if ($request->filled('search')) {
            $search = trim($request->input('search'));
            $query->where(function ($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                    ->orWhere('ip_address', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($uq) use ($search) {
                        $uq->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    });
            });
        }

        $logs = $query->latest('id')->paginate(20)->withQueryString();

        $actionTypes = [
            'login' => 'Login User',
            'logout' => 'Logout User',
            'register' => 'Registrasi Akun',
            'quiz_started' => 'Mulai Quiz Pengetahuan',
            'quiz_completed' => 'Selesai Quiz Pengetahuan',
            'keterampilan_started' => 'Mulai Quiz Keterampilan',
            'keterampilan_completed' => 'Selesai Quiz Keterampilan',
            'user_created' => 'Admin Buat User',
            'user_updated' => 'Admin Update User',
            'user_deleted' => 'Admin Hapus User',
        ];

        return view('admin.activity_logs.index', [
            'logs' => $logs,
            'actionTypes' => $actionTypes,
            'currentAction' => $request->input('action', ''),
            'currentSearch' => $request->input('search', ''),
        ]);
    }

    /**
     * Clear activity logs older than specified days.
     */
    public function purgeOldLogs(Request $request): RedirectResponse
    {
        $days = (int) $request->input('days', 30);
        $days = max(7, min(365, $days));

        $count = ActivityLog::where('created_at', '<', now()->subDays($days))->delete();

        return redirect()->route('admin.activity-logs.index')
            ->with('success', "Berhasil membersihkan {$count} catatan aktivitas yang lebih lama dari {$days} hari.");
    }
}
