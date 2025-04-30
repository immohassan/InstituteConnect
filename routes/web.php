<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\ResourceController;
use App\Http\Controllers\EventsController;
use App\Http\Controllers\SocietyController;
use App\Http\Controllers\NotificationsController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

// Include auth routes
require __DIR__.'/auth.php';

// Dashboard route
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
Route::post('/follow/{user}', [UserController::class, 'follow'])->name('follow');
Route::post('/unfollow/{user}', [UserController::class, 'unfollow'])->name('unfollow');


// Profile routes
Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
Route::put('/profile/edit', [UserController::class, 'update'])->name('profile.update');
Route::get('/profile/{id}', [ProfileController::class, 'index'])->name('profile.show');
Route::get('/search', [SearchController::class, 'index'])->name('profile.search');
Route::get('/search-users', [UserController::class, 'search'])->name('users.search');
Route::get('/static-suggestions', [UserController::class, 'staticSuggestions'])->name('users.static');

// Post routes
Route::resource('posts', 'App\Http\Controllers\PostController');
Route::post('posts/create',[PostController::class, 'user_post_create'])->name('users_post.create');
// Route::post('/posts/{post}/like', 'App\Http\Controllers\PostController@like')->name('posts.like');
Route::post('/posts/{post}/toggle-like', [PostController::class, 'toggleLike']);
Route::post('/posts/post-comment', [PostController::class, 'post_comment'])->name('comments.store');


// Comment routes
// Route::resource('comments', 'App\Http\Controllers\CommentController')->only(['store', 'update', 'destroy']);

// Society routes
// Route::resource('societies', 'App\Http\Controllers\SocietyController');
Route::get('/societies',[SocietyController::class, 'index'])->name('societies');

// Announcement routes
Route::resource('announcements', 'App\Http\Controllers\AnnouncementController');
Route::get('/home', [HomeController::class, 'index'])->name('home');

//Events Routes
Route::get('/events', [EventsController::class, 'index'])->name('events');
// Resource center routes
Route::get('/resources', [ResourceController::class, 'index'])->name('resources');
Route::get('/resources/attendance', 'App\Http\Controllers\ResourceController@attendance')->name('resources.attendance');

// Subject routes
Route::resource('subjects', 'App\Http\Controllers\SubjectController');


//Notifications
Route::get('/sendNotif/like', [NotificationsController::class, 'send_like_notif'])->name('notif.like');
Route::get('/sendNotif/comment', [NotificationsController::class, 'send_comment_notif'])->name('notif.comment');
Route::get('/sendNotif/post', [NotificationsController::class, 'send_post_notif'])->name('notif.post');
Route::get('/sendNotif/follow', [NotificationsController::class, 'send_follow_notif'])->name('notif.follow');
Route::post('/save-subscription-id', [UserController::class, 'saveSubscriptionId']);
Route::get('/get-notifications', [NotificationsController::class, 'navbarNotifications']);

//Footer
Route::view('/terms', 'footer.terms')->name('terms');
Route::view('/privacy', 'footer.privacy')->name('privacy');
Route::view('/contact', 'footer.contact')->name('contact');