<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

use App\Livewire\Pages\Login;
use App\Livewire\Pages\Auth\Dashboard;
use App\Livewire\Pages\Auth\Product\Index as IndexProduct;
use App\Livewire\Pages\Auth\Product\Create;
use App\Livewire\Pages\Auth\Product\Edit;
use App\Livewire\Pages\Auth\Product\View;
use App\Livewire\Pages\Auth\Stock\Index as IndexStock;
use App\Livewire\Pages\Auth\Stock\Add as AddStock;
use App\Livewire\Pages\Auth\Stock\View as ViewStock;
use App\Livewire\Pages\Auth\Order\Create as CreateOrder;

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

                Route::name('order.')->prefix('order')
                    ->group(function(){
                        Volt::route('/', 'pages.auth.order.index')->name('index');
                        Route::get('/create', CreateOrder::class)->name('create');
                        Volt::route('/view/{order}', 'pages.auth.order.view')->name('view');
                    });

                // stock route group
                Route::name('stock.')->prefix('stock')
                    ->group(function(){
                        Route::get('/', IndexStock::class)->name('index');
                        Route::get('add/{product}', AddStock::class)->name('add');
                        Route::get('view/{stock}', ViewStock::class)->name('view');
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
