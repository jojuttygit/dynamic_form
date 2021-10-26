<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{FormController, PublicController};

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
    return view('welcome');
});

Auth::routes();

Route::get('public/show-form/{custom_form_id}', [PublicController::class, 'showForm'])->name('public.show-form');

Route::get('forms/create-field', [FormController::class, 'createField']);
Route::resource('forms', FormController::class);
