<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FormController;
use App\Http\Controllers\HelpController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PaymentMethodController;

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
    // Existing profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Fix the route names to match those used in the view
    Route::post('/profile/photo', [ProfileController::class, 'updateProfilePhoto'])->name('profile.photo.update');
    Route::delete('/profile/photo', [ProfileController::class, 'removeProfilePhoto'])->name('profile.photo.remove');
});

Route::middleware('auth')->group(function () {
    // Project routes
    Route::resource('projects', ProjectController::class);
    
    // Form routes
    Route::get('projects/{project}/forms', [FormController::class, 'index'])->name('projects.forms.index');
    Route::get('projects/{project}/forms/create', [FormController::class, 'create'])->name('projects.forms.create');
    Route::post('projects/{project}/forms', [FormController::class, 'store'])->name('projects.forms.store');
    Route::get('projects/{project}/forms/{form}', [FormController::class, 'show'])->name('projects.forms.show');
    Route::get('projects/{project}/forms/{form}/edit', [FormController::class, 'edit'])->name('projects.forms.edit');
    Route::put('projects/{project}/forms/{form}', [FormController::class, 'update'])->name('projects.forms.update');
    Route::delete('projects/{project}/forms/{form}', [FormController::class, 'destroy'])->name('projects.forms.destroy');
});

// Public form routes (no auth required)
Route::get('forms/{form}', [FormController::class, 'showPublic'])->name('forms.public');
Route::post('forms/{form}/submit', [FormController::class, 'submitPublic'])->name('forms.submit');
Route::get('forms/response/{formResponse}/thank-you', [FormController::class, 'thankYou'])->name('forms.thank-you');
Route::get('orders/{order}/thank-you', [FormController::class, 'orderThankYou'])->name('orders.thank-you');

// Product routes
Route::get('projects/{project}/products', [ProductController::class, 'index'])->name('projects.products.index');
Route::get('projects/{project}/products/create', [ProductController::class, 'create'])->name('projects.products.create');
Route::post('projects/{project}/products', [ProductController::class, 'store'])->name('projects.products.store');
Route::get('projects/{project}/products/{product}', [ProductController::class, 'show'])->name('projects.products.show');
Route::get('projects/{project}/products/{product}/edit', [ProductController::class, 'edit'])->name('projects.products.edit');
Route::put('projects/{project}/products/{product}', [ProductController::class, 'update'])->name('projects.products.update');
Route::delete('projects/{project}/products/{product}', [ProductController::class, 'destroy'])->name('projects.products.destroy');

// Order routes
Route::get('projects/{project}/orders', [OrderController::class, 'index'])->name('projects.orders.index');
Route::get('projects/{project}/orders/{order}', [OrderController::class, 'show'])->name('projects.orders.show');
Route::put('projects/{project}/orders/{order}', [OrderController::class, 'update'])->name('projects.orders.update');
Route::post('projects/{project}/orders/{order}/payments', [OrderController::class, 'recordPayment'])->name('projects.orders.payments.store');
Route::put('projects/{project}/orders/{order}/payments/{payment}/verify', [OrderController::class, 'verifyPayment'])->name('projects.orders.payments.verify');
Route::put('projects/{project}/orders/{order}/payments/{payment}/reject', [OrderController::class, 'rejectPayment'])->name('projects.orders.payments.reject');
Route::delete('projects/{project}/orders/{order}/payments/{payment}', [OrderController::class, 'deletePayment'])->name('projects.orders.payments.destroy');

// Invoice routes
Route::post('projects/{project}/orders/{order}/invoices', [InvoiceController::class, 'generate'])->name('projects.orders.invoices.generate');
Route::get('projects/{project}/invoices/{invoice}', [InvoiceController::class, 'show'])->name('projects.invoices.show');
Route::get('projects/{project}/invoices/{invoice}/download', [InvoiceController::class, 'download'])->name('projects.invoices.download');
Route::post('projects/{project}/invoices/{invoice}/regenerate', [InvoiceController::class, 'regenerate'])->name('projects.invoices.regenerate');
Route::get('projects/{project}/invoices/{invoice}/share-whatsapp', [InvoiceController::class, 'shareWhatsApp'])->name('projects.invoices.share.whatsapp');

// Payment Method routes
Route::get('projects/{project}/payment-methods', [PaymentMethodController::class, 'index'])->name('projects.payment-methods.index');
Route::get('projects/{project}/payment-methods/create', [PaymentMethodController::class, 'create'])->name('projects.payment-methods.create');
Route::post('projects/{project}/payment-methods', [PaymentMethodController::class, 'store'])->name('projects.payment-methods.store');
Route::get('projects/{project}/payment-methods/{paymentMethod}/edit', [PaymentMethodController::class, 'edit'])->name('projects.payment-methods.edit');
Route::put('projects/{project}/payment-methods/{paymentMethod}', [PaymentMethodController::class, 'update'])->name('projects.payment-methods.update');
Route::delete('projects/{project}/payment-methods/{paymentMethod}', [PaymentMethodController::class, 'destroy'])->name('projects.payment-methods.destroy');

// Public invoice route
Route::get('invoices/{invoice}/public', [InvoiceController::class, 'publicView'])->name('invoices.public');

// Dashboard
Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

// Create order from form response
Route::get('projects/{project}/forms/response/{formResponse}/order/create', [OrderController::class, 'createFromResponse'])->name('projects.forms.response.order.create');

// Financial Reports
Route::get('projects/{project}/reports/financial', [ReportController::class, 'financialReport'])->name('projects.reports.financial');
Route::get('projects/{project}/exports/orders', [ReportController::class, 'exportOrdersCSV'])->name('projects.exports.orders');
Route::get('projects/{project}/exports/payments', [ReportController::class, 'exportPaymentsCSV'])->name('projects.exports.payments');

// Notifications
Route::middleware('auth')->prefix('notifications')->name('notifications.')->group(function() {
    Route::get('/', [NotificationController::class, 'index'])->name('index');
    Route::get('/unread', [NotificationController::class, 'getUnread'])->name('unread');
    Route::post('/mark-as-read/{notification}', [NotificationController::class, 'markAsRead'])->name('markAsRead');
    Route::post('/mark-all-as-read', [NotificationController::class, 'markAllAsRead'])->name('markAllAsRead');
});

// Help and FAQ
Route::middleware('auth')->group(function() {
    Route::get('/faq', [HelpController::class, 'faq'])->name('faq');
    Route::get('/user-guide', [HelpController::class, 'userGuide'])->name('user-guide');
});

require __DIR__.'/auth.php';