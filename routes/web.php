<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\BlogController;
use Illuminate\Support\Facades\Auth;


Route::get('/', [AdminController::class, 'home']);


Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard'); 
    })->name('dashboard');
});

Route::get('/home', [AdminController::class, 'index'])->name('home')->middleware('auth');

// Public Routes
Route::get('/room_details/{id}', [HomeController::class, 'room_details'])->name('room_details');
Route::get('/search_rooms_ajax', [HomeController::class, 'search_rooms_ajax'])->name('search_rooms_ajax');
Route::post('/book_room/{id}', [HomeController::class, 'book_room'])->name('book_room');
Route::get('/checkout/{id}', [HomeController::class, 'checkout'])->name('checkout');
Route::post('/process_checkout/{id}', [HomeController::class, 'process_checkout'])->name('process_checkout');
Route::get('/our_rooms', [HomeController::class, 'our_rooms'])->name('our_rooms');
Route::post('/contact_store', [AdminController::class, 'contact_store'])->name('contact_store');

Route::get('/blog/{id}', [BlogController::class, 'show'])->name('blog.show');
Route::post('/blog/{id}/comment', [BlogController::class, 'postComment'])->name('blog.comment');

// Admin Routes
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/create_room', [AdminController::class, 'create_room']);
    Route::post('/add_room', [AdminController::class, 'add_room']);
    Route::get('/view_rooms', [AdminController::class, 'view_rooms']);
    Route::post('/room_delete/{id}', [AdminController::class, 'room_delete'])->name('room_delete');
    Route::get('/update_room/{id}', [AdminController::class, 'update_room']);
    Route::post('/edit_room/{id}', [AdminController::class, 'edit_room'])->name('edit_room'); 
    
    Route::get('/bookings', [AdminController::class, 'bookings']);
    Route::get('/create_booking', [AdminController::class, 'create_booking']);
    Route::post('/store_booking', [AdminController::class, 'store_booking']);
    Route::get('/edit_booking/{id}', [AdminController::class, 'edit_booking']);
    Route::post('/update_booking/{id}', [AdminController::class, 'update_booking']);
    Route::post('/delete_booking/{id}', [AdminController::class, 'delete_booking'])->name('delete_booking');
    Route::post('approve_booking/{id}', [AdminController::class, 'approve_booking']);
    Route::post('reject_booking/{id}', [AdminController::class, 'reject_booking']);
    Route::get('/booking_calendar', [AdminController::class, 'booking_calendar']);

    Route::get('/view_gallery', [AdminController::class, 'view_gallery']);
    Route::post('/upload_gallery', [AdminController::class, 'upload_gallery'])->name('upload_gallery');
    Route::post('/delete_gallery/{id}', [AdminController::class, 'delete_gallery']);

    Route::get('/messages', [AdminController::class, 'messages']);
    Route::get('/locale/{locale}', [AdminController::class, 'setLocale'])->name('locale.switch');
    Route::get('/send_mail/{id}', [AdminController::class, 'send_mail']);
    Route::post('/send_mail/{id}', [AdminController::class, 'sendEmail'])->name('send_mail');

    Route::get('/blog_categories', [AdminController::class, 'view_blog_categories']);
    Route::post('/add_blog_category', [AdminController::class, 'add_blog_category'])->name('add_blog_category');
    Route::post('/delete_blog_category/{id}', [AdminController::class, 'delete_blog_category'])->name('delete_blog_category');

    Route::get('/blogs', [AdminController::class, 'view_blogs']);
    Route::get('/create_blog', [AdminController::class, 'create_blog']);
    Route::post('/store_blog', [AdminController::class, 'store_blog'])->name('store_blog');
    Route::get('/edit_blog/{id}', [AdminController::class, 'edit_blog']);
    Route::post('/update_blog/{id}', [AdminController::class, 'update_blog'])->name('update_blog');
    Route::post('/delete_blog/{id}', [AdminController::class, 'delete_blog'])->name('delete_blog');
});
