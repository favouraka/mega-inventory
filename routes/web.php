<?php

use Illuminate\Support\Facades\Route;

use App\Livewire\Pages\Login;
use App\Livewire\Pages\Auth\Dashboard;
use App\Livewire\Pages\Auth\Product\Index;
use App\Livewire\Pages\Auth\Product\Create;

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
    return view('pages.index');
});


Route::prefix('dashboard')
     ->name('dashboard.')
     ->middleware('auth')
     ->group(function(){
                Route::get('/', Dashboard::class)->name('home');
                // product routes
                Route::name('product.')
                      ->prefix('product')
                      ->group(function(){
                                Route::get('/', Index::class)->name('index');
                                Route::get('create', Create::class)->name('create');
                            });
            });

Route::get( '/login' , Login::class )->middleware('guest')->name('login');
