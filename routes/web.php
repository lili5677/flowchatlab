<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\QuizKeterampilanController;
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

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');
