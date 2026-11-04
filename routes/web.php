<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DemoAdminController;
use App\Http\Controllers\DemoCheckoutController;
use App\Http\Controllers\DemoController;
use App\Http\Controllers\WebhookController;
use App\Http\Middleware\ResolveDemo;
use App\Http\Middleware\SelectAdminDemo;
use App\Http\Middleware\EnsureDemoAdmin;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => response('<!doctype html><html lang="en"><meta charset="utf-8"><meta name="robots" content="noindex, nofollow"><title>Demo environment</title><body>Demo environment</body></html>')
    ->header('X-Robots-Tag', 'noindex, nofollow'))->name('home');

Route::get('/admin/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/admin/login', [AuthController::class, 'login'])->middleware('throttle:5,1')->name('login.submit');
Route::post('/admin/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

Route::prefix('admin')->name('admin.')->middleware(['auth', EnsureDemoAdmin::class, SelectAdminDemo::class])->group(function () {
    Route::get('/', [DemoAdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/tickets/export', [DemoAdminController::class, 'export'])->name('export');
    Route::get('/tickets/{ticket}', [DemoAdminController::class, 'edit'])->whereNumber('ticket')->name('ticket.edit');
    Route::patch('/tickets/{ticket}', [DemoAdminController::class, 'update'])->whereNumber('ticket')->name('ticket.update');
    Route::post('/tickets/{ticket}/approve', [DemoAdminController::class, 'approve'])->whereNumber('ticket')->name('ticket.approve');
    Route::get('/tickets/{ticket}/document', [DemoAdminController::class, 'document'])->whereNumber('ticket')->name('ticket.document');
    Route::post('/tickets', [DemoAdminController::class, 'create'])->name('ticket.create');
    Route::post('/imports', [DemoAdminController::class, 'import'])->name('import');
    Route::post('/prices', [DemoAdminController::class, 'price'])->name('price');
    Route::post('/discounts', [DemoAdminController::class, 'discount'])->name('discount');
    Route::delete('/discounts/{discount}', [DemoAdminController::class, 'deleteDiscount'])->whereNumber('discount')->name('discount.delete');
});

Route::post('/webhook/stripe', [WebhookController::class, 'handleStripe'])->name('webhook.stripe');

Route::get('/{demo}/assets/{path}', [DemoController::class, 'asset'])
    ->where('path', '.*')->middleware(ResolveDemo::class)->name('demo.asset');

Route::prefix('{demo}')->middleware(ResolveDemo::class)->group(function () {
    Route::post('/iscrizione/{race}', [DemoCheckoutController::class, 'store'])
        ->middleware('throttle:10,1')->name('demo.checkout.store');
    Route::get('/checkout/{order_code}/payment', [DemoCheckoutController::class, 'payment'])->name('demo.checkout.payment');
    Route::get('/checkout/{order_code}/success', [DemoCheckoutController::class, 'success'])->name('demo.checkout.success');
    Route::get('/checkout/{order_code}/cancel', [DemoCheckoutController::class, 'cancel'])->name('demo.checkout.cancel');
    Route::get('/ticket/{unique_code}', [DemoCheckoutController::class, 'ticket'])->name('demo.ticket');

    Route::prefix('en')->name('demo.en.')->group(function () {
        Route::get('/', [DemoController::class, 'home'])->name('home');
        Route::get('/gare/{race}', [DemoController::class, 'race'])->name('race');
        Route::get('/iscrizione/{race}', [DemoController::class, 'registration'])->name('registration');
        Route::get('/{page}', [DemoController::class, 'page'])->name('page');
    });

    Route::name('demo.')->group(function () {
        Route::get('/', [DemoController::class, 'home'])->name('home');
        Route::get('/gare/{race}', [DemoController::class, 'race'])->name('race');
        Route::get('/iscrizione/{race}', [DemoController::class, 'registration'])->name('registration');
        Route::get('/{page}', [DemoController::class, 'page'])->name('page');
    });
});
