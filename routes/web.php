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

Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});

Route::middleware('auth')->group(function () {
    // Profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/profile/photo', [ProfileController::class, 'updateProfilePhoto'])->name('profile.photo.update');
    Route::delete('/profile/photo', [ProfileController::class, 'removeProfilePhoto'])->name('profile.photo.remove');
    
    // Project routes
    Route::resource('projects', ProjectController::class);
    
    // Form routes - Basic CRUD
    Route::get('projects/{project}/forms', [FormController::class, 'index'])->name('projects.forms.index');
    Route::get('projects/{project}/forms/create', [FormController::class, 'create'])->name('projects.forms.create');
    Route::post('projects/{project}/forms', [FormController::class, 'store'])->name('projects.forms.store');
    Route::get('projects/{project}/forms/{form}', [FormController::class, 'show'])->name('projects.forms.show');
    Route::get('projects/{project}/forms/{form}/edit', [FormController::class, 'edit'])->name('projects.forms.edit');
    Route::put('projects/{project}/forms/{form}', [FormController::class, 'update'])->name('projects.forms.update');
    Route::delete('projects/{project}/forms/{form}', [FormController::class, 'destroy'])->name('projects.forms.destroy');
    
    // Form routes - Additional actions
    Route::post('projects/{project}/forms/{form}/toggle-active', [FormController::class, 'toggleActive'])->name('projects.forms.toggle-active');
    Route::post('projects/{project}/forms/{form}/update-closing-date', [FormController::class, 'updateClosingDate'])->name('projects.forms.update-closing-date');
    Route::delete('projects/{project}/forms/{form}/remove-closing-date', [FormController::class, 'removeClosingDate'])->name('projects.forms.remove-closing-date');
    Route::post('projects/{project}/forms/{form}/clone', [FormController::class, 'clone'])->name('projects.forms.clone');
    Route::post('projects/{project}/forms/{form}/save-as-template', [FormController::class, 'saveAsTemplate'])->name('projects.forms.save-as-template');
    
    // Form response management
    Route::get('projects/{project}/forms/{form}/responses', [FormController::class, 'responses'])->name('projects.forms.responses');
    Route::get('projects/{project}/forms/{form}/responses/{response}', [FormController::class, 'viewResponse'])->name('projects.forms.view-response');
    Route::delete('projects/{project}/forms/{form}/responses/{response}', [FormController::class, 'deleteResponse'])->name('projects.forms.delete-response');
    Route::get('projects/{project}/forms/{form}/responses/{response}/create-order', [FormController::class, 'createOrderFromResponse'])->name('projects.forms.create-order');
    Route::post('projects/{project}/forms/{form}/responses/{response}/create-order', [FormController::class, 'storeOrderFromResponse'])->name('projects.forms.store-order');
    
    // Form templates
    Route::get('projects/{project}/forms/templates', [FormController::class, 'templates'])->name('projects.forms.templates');
    Route::get('projects/{project}/forms/template/{template}/create', [FormController::class, 'createFromTemplate'])->name('projects.forms.create-from-template');
    
    // Form exports
    Route::get('projects/{project}/forms/{form}/export-csv', [FormController::class, 'exportResponsesCSV'])->name('projects.forms.export-csv');
    Route::get('projects/{project}/forms/{form}/export-pdf', [FormController::class, 'exportResponsesPDF'])->name('projects.forms.export-pdf');
    
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
    
    // Financial Reports
    Route::get('projects/{project}/reports/financial', [ReportController::class, 'financialReport'])->name('projects.reports.financial');
    Route::get('projects/{project}/exports/orders', [ReportController::class, 'exportOrdersCSV'])->name('projects.exports.orders');
    Route::get('projects/{project}/exports/payments', [ReportController::class, 'exportPaymentsCSV'])->name('projects.exports.payments');
    
    // Notifications
    Route::prefix('notifications')->name('notifications.')->group(function() {
        Route::get('/', [NotificationController::class, 'index'])->name('index');
        Route::get('/unread', [NotificationController::class, 'getUnread'])->name('unread');
        Route::post('/mark-as-read/{notification}', [NotificationController::class, 'markAsRead'])->name('markAsRead');
        Route::post('/mark-all-as-read', [NotificationController::class, 'markAllAsRead'])->name('markAllAsRead');
    });
    
    // Help and FAQ
    Route::get('/faq', [HelpController::class, 'faq'])->name('faq');
    Route::get('/user-guide', [HelpController::class, 'userGuide'])->name('user-guide');
});

// Public routes - no authentication required
Route::get('forms/{form}', [FormController::class, 'showPublic'])->name('forms.public');
Route::post('forms/{form}/submit', [FormController::class, 'submitPublic'])->name('forms.submit');
Route::get('forms/response/{formResponse}/thank-you', [FormController::class, 'thankYou'])->name('forms.thank-you');
Route::get('orders/{order}/thank-you', [FormController::class, 'orderThankYou'])->name('orders.thank-you');
Route::get('invoices/{invoice}/public', [InvoiceController::class, 'publicView'])->name('invoices.public');

require __DIR__.'/auth.php';