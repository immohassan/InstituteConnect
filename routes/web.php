<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\ResourceController;
use App\Http\Controllers\EventsController;
use App\Http\Controllers\SocietyController;
use App\Http\Controllers\NotificationsController;
use App\Http\Controllers\AnnouncementController;
use App\Models\Announcement;

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
Route::get('/admin-dashboard', [DashboardController::class, 'index'])->name('dashboard');
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
Route::post('posts/admin-create',[AnnouncementController::class, 'admin_post_create'])->name('admin_post.create');
// Route::post('/posts/{post}/like', 'App\Http\Controllers\PostController@like')->name('posts.like');
Route::post('/posts/{post}/toggle-like', [PostController::class, 'toggleLike'])->name('posts.like');
Route::post('/posts/post-comment', [PostController::class, 'post_comment'])->name('comments.store');


//Admin Portal 
Route::get('/admin-portal', [AdminController::class, 'admin_portal_show'])->name('admin.portal');
Route::delete('/admin-portal/delete/{id}', [AdminController::class, 'user_delete'])->name('user.delete');
Route::post('/admin-portal/update/{id}', [AdminController::class, 'user_update'])->name('user.update');
Route::post('/admin-portal/add', [AdminController::class, 'user_add'])->name('user.add');

// Society routes
// Route::resource('societies', 'App\Http\Controllers\SocietyController');
Route::get('/societies',[SocietyController::class, 'index'])->name('societies');
Route::get('/society/profile/{id}',[SocietyController::class, 'show'])->name('societies.show');
Route::get('/society/profile/edit/{id}',[SocietyController::class, 'edit'])->name('society.edit');
Route::post('/society/profile/update/',[SocietyController::class, 'update'])->name('society.update');
Route::delete('/society/profile/delete/{id}',[SocietyController::class, 'delete'])->name('society.delete');
Route::get('/society/new/',[SocietyController::class, 'new'])->name('society.new');
Route::post('/society/add/',[SocietyController::class, 'add'])->name('society.add');
Route::post('/society/{id}/follow', [SocietyController::class, 'follow'])->name('society.follow');
Route::post('/society/{id}/unfollow', [SocietyController::class, 'unfollow'])->name('society.unfollow');


// Announcement routes
Route::resource('announcements', 'App\Http\Controllers\AnnouncementController');
Route::get('/home', [HomeController::class, 'index'])->name('home');

//Events Routes
Route::get('/events', [EventsController::class, 'index'])->name('events');
// Resource center routes
Route::get('/resources', [ResourceController::class, 'index'])->name('resources');
// Route::get('/resources/{id}', [ResourceController::class, 'show'])->name('resources.show');
Route::get('/resources/{semester_id}/{subject_name}', [ResourceController::class, 'show'])->name('resources.show');
Route::post('/resources/add', [ResourceController::class, 'add'])->name('resources.add');
Route::delete('/resources/delete/{id}', [ResourceController::class, 'delete'])->name('resources.delete'); 
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