<?php
use App\Http\Controllers\QuizController;
use Illuminate\Support\Facades\Route;

Route::get('/', [QuizController::class, 'index'])->name('quiz.index');
Route::get('/quiz/create', [QuizController::class, 'create'])->name('quiz.create');  // ← MUST be before {id}
Route::get('/quiz/result/{id}', [QuizController::class, 'result'])->name('quiz.result');
Route::post('/quiz/attempt', [QuizController::class, 'attempt'])->name('quiz.attempt');
Route::get('/quiz/{id}', [QuizController::class, 'show'])->name('quiz.show');        // ← {id} goes last