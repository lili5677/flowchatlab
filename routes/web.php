<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\QuizController;
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

Route::get('/simulasi', function () {
    return view('simulasi');
})->name('simulasi');

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Quiz Routes (Protected by Auth)
Route::middleware('auth')->prefix('quiz')->name('quiz.')->group(function () {
    Route::get('/', [QuizController::class, 'index'])->name('index');
    Route::post('/retry', [QuizController::class, 'retry'])->name('retry');
    Route::get('/{attempt}', [QuizController::class, 'show'])->name('show');
    Route::post('/{attempt}/answer', [QuizController::class, 'answer'])->name('answer');
    Route::post('/{attempt}/finish', [QuizController::class, 'finish'])->name('finish');
    Route::get('/hasil/{attempt}', [QuizController::class, 'result'])->name('result');
});
