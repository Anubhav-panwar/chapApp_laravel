<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ChatController;
use Illuminate\Support\Facades\Route;

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

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');


    // Route::chat('/chat', [ChatController::class, 'chat'])->name('chat');

});
Route::controller(ChatController::class)->group(function(){
    Route::match(['GET', 'POST'],'/all/chat' , 'Allchat')->name('all.chat');
    Route::match(['GET', 'POST'],'/showdetails' , 'Showdetails')->name('show.details');
    Route::match(['GET', 'POST'],'/save/chat', 'SaveChat')->name('save.chat');

});

require __DIR__.'/auth.php';
