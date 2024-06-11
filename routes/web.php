<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;

use App\Livewire\Home;
use App\Livewire\Profile\Profile;
use App\Livewire\Data\Basal;
use App\Livewire\Data\Records;
use App\Livewire\Data\CGM;
use App\Livewire\Data\Experiments;
use App\Livewire\Help;

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
    return redirect()->route('home');
});

Route::group(['middleware' => 'guest'], function () {
    Route::get('/telegram-login', [AuthController::class, 'TGLogin'])->name('tg.login');

    Route::get('/login', [AuthController::class, 'loginGet'])->name('login');
    Route::post('/login', [AuthController::class, 'loginPost'])->name('login.send');

    Route::get('/register', [AuthController::class, 'registerGet'])->name('register');
    Route::post('/register', [AuthController::class, 'registerPost'])->name('register.send');  
});

Route::group(['middleware' => 'auth'], function () {
    Route::post('/logout', [AuthController::class, 'logoutPost'])->name('logout');

    Route::get('/profile/telegram-connect', [AuthController::class, 'TGConnect'])->name('tg.connect');
    Route::get('/profile/{current_tab?}', Profile::class)->name('profile');

    Route::get('/home/{current_tab?}', Home::class)->name('home');
    Route::get('/basal/{id?}', Basal::class)->name('basal');
    Route::get('/records/{current_tab?}', Records::class)->name('records');
    Route::get('/experiments/{current_tab?}', Experiments::class)->name('experiments');
    Route::get('/cgm/{current_tab?}', CGM::class)->name('cgm');

    Route::get('/help/{current_tab?}', Help::class)->name('help');
});

