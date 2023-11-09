<?php

use App\Http\Controllers\AnswerController;
use App\Http\Controllers\QuestionController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return Inertia('Home');
});
Route::get('questions', [QuestionController::class, 'index'])->name('index.controller');
Route::post('/questions', [QuestionController::class, 'store'])->name('store.questions');
Route::put('/answers', [AnswerController::class, 'update'])->name('answer.update');
Route::delete('/question/{question}', [QuestionController::class, 'destroy']);
