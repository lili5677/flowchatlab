<?php

use App\Http\Controllers\Admin\AdminActivityLogController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminProgressController;
use App\Http\Controllers\Admin\AdminQuizKeterampilanController;
use App\Http\Controllers\Admin\AdminQuizPengetahuanController;
use App\Http\Controllers\Admin\AdminSimulationController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\QuizKeterampilanController;
use App\Models\Simulation;
use Database\Seeders\SimulationSeeder;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
})->name('home');

Route::get('/home', function () {
    return view('home');
});

Route::get('/materi', function () {
    return view('materi');
})->name('materi');

// Quiz Keterampilan Routes
Route::get('/quiz/keterampilan', [QuizKeterampilanController::class, 'index'])->name('quiz.keterampilan');
Route::post('/quiz/keterampilan/mulai', [QuizKeterampilanController::class, 'mulai'])->name('quiz.keterampilan.mulai');
Route::get('/quiz/keterampilan/tugas', [QuizKeterampilanController::class, 'tugas'])->name('quiz.keterampilan.tugas');
Route::post('/quiz/keterampilan/submit', [QuizKeterampilanController::class, 'submit'])->name('quiz.keterampilan.submit');
Route::post('/quiz/keterampilan/next', [QuizKeterampilanController::class, 'next'])->name('quiz.keterampilan.next');
Route::get('/quiz/keterampilan/hasil', [QuizKeterampilanController::class, 'hasil'])->name('quiz.keterampilan.hasil');
Route::post('/quiz/keterampilan/reset', [QuizKeterampilanController::class, 'reset'])->name('quiz.keterampilan.reset');

Route::get('/simulasi', function () {
    $simulations = Simulation::where('is_active', true)->orderBy('order')->orderBy('level_number')->get();
    if ($simulations->isEmpty()) {
        (new SimulationSeeder)->run();
        $simulations = Simulation::where('is_active', true)->orderBy('order')->orderBy('level_number')->get();
    }

    return view('simulasi', compact('simulations'));
})->name('simulasi');

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Profile Routes (Protected by Auth)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
});

// Quiz Pengetahuan Landing (public - handles guest via controller)
Route::get('/quiz', [QuizController::class, 'index'])->name('quiz.index');

// Quiz Routes (Protected by Auth)
Route::middleware('auth')->prefix('quiz')->name('quiz.')->group(function () {
    Route::post('/retry', [QuizController::class, 'retry'])->name('retry');
    Route::get('/{attempt}', [QuizController::class, 'show'])->name('show');
    Route::post('/{attempt}/answer', [QuizController::class, 'answer'])->name('answer');
    Route::post('/{attempt}/finish', [QuizController::class, 'finish'])->name('finish');
    Route::post('/{attempt}/exit', [QuizController::class, 'exit'])->name('exit');
    Route::get('/hasil/{attempt}', [QuizController::class, 'result'])->name('result');
});

// Admin Panel Routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');

    // User Management
    Route::resource('users', AdminUserController::class);

    // Manajemen Quiz Pengetahuan
    Route::resource('quiz-pengetahuan', AdminQuizPengetahuanController::class);

    // Manajemen Quiz Keterampilan
    Route::resource('quiz-keterampilan', AdminQuizKeterampilanController::class);

    // Manajemen Simulasi
    Route::resource('simulasi', AdminSimulationController::class);

    // Progress Siswa & Statistik
    Route::get('/progress', [AdminProgressController::class, 'index'])->name('progress.index');
    Route::get('/progress/export', [AdminProgressController::class, 'export'])->name('progress.export');
    Route::get('/progress/print', [AdminProgressController::class, 'print'])->name('progress.print');
    Route::get('/progress/quiz/{attempt}', [AdminProgressController::class, 'quizDetail'])->name('progress.quiz-detail');

    // Activity Logs
    Route::get('/activity-logs', [AdminActivityLogController::class, 'index'])->name('activity-logs.index');
    Route::post('/activity-logs/purge', [AdminActivityLogController::class, 'purgeOldLogs'])->name('activity-logs.purge');
});
