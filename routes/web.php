<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\XmlImportController;
use App\Http\Controllers\XmlControllerNew;
use App\Http\Controllers\LevelController;

use App\Http\Controllers\UserController;
use App\Http\Controllers\SoundController;


use App\Http\Controllers\IsolatedWordsController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/



Route::get('/', function () {
    return view('welcome');
});
Route::get('/insertdata', [XmlImportController::class, 'importFromXml']);
// Route::get('/import/ebrahim', [XmlControllerNew::class, 'importEbrahimXML']);

//Route::get('/import-isolated', [IsolatedWordsController::class, 'importIsolatedWords']);

Route::get('/levels', [LevelController::class, 'index']);
// 2. Show users of a specific level
Route::get('/levels/{level}/users', [LevelController::class, 'showUsers']);

// 3. Show all sounds for a specific user
Route::get('/users/{user}/sounds', [UserController::class, 'showSounds']);

// 4. Show full details of a specific sound (sentences + words)
Route::get('/sounds/{id}', [SoundController::class, 'show'])->name('sounds.show');
// 5. Optional: Filter by sound type (e.g., reading, picture description, narration)
Route::get('/sounds/type/{type}', [SoundController::class, 'filterByType']);

