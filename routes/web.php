<?php

use Illuminate\Support\Facades\Route;

use App\Livewire\Pages\Login;
use App\Livewire\Pages\Auth\Dashboard;
use App\Livewire\Pages\Auth\Product\Index as IndexProduct;
use App\Livewire\Pages\Auth\Product\Create;
use App\Livewire\Pages\Auth\Product\Edit;
use App\Livewire\Pages\Auth\Product\View;
use App\Livewire\Pages\Auth\Stock\Index as IndexStock;
use App\Livewire\Pages\Auth\Stock\Add as AddStock;

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
    // return view('pages.index');
    return redirect()->route('login');
});


Route::prefix('dashboard')->name('dashboard.')->middleware('auth')
     ->group(function(){
                Route::get('/', Dashboard::class)->name('home');
                // stock route group
                Route::name('stock.')->prefix('stock')
                    ->group(function(){
                        Route::get('/', IndexStock::class)->name('index');
                        Route::get('add/{product}', AddStock::class)->name('add');
                    });
                // product routes
                Route::name('product.')->prefix('product')
                      ->group(function(){
                                Route::get('/', IndexProduct::class)->name('index');
                                Route::get('create', Create::class)->name('create');
                                Route::get('edit/{product}', Edit::class)->name('edit');
                                Route::get('view/{product}', View::class)->name('view');
                            });
            });

Route::get( '/login' , Login::class )->middleware('guest')->name('login');
