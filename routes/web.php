<?php
use App\Http\Controllers\WebController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\LineController;
use App\Http\Controllers\Admin\MemberManagerController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\SlipManagerController;
use Illuminate\Support\Facades\Route;

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

Route::get('/cover', [WebController::class, 'cover'])->name('cover');
Route::prefix('/')->middleware(['checkLive'])->group(function () {
    Route::get('', [WebController::class, 'index'])->name('home');
    Route::get('/signup', [WebController::class, 'signup'])->name('signup');
    Route::post('/createmember', [WebController::class, 'storemember'])->name('create.member');
    Route::post('/creatememberpassword', [WebController::class, 'storememberpassword'])->name('create.member.password');
    Route::get('/lineauth', [LineController::class, 'auth'])->name('lineauth');
    Route::post('/storesignin', [WebController::class, 'storesignin'])->name('member.storesignin');
    Route::post('/memberauth', [WebController::class, 'auth'])->name('memberauth');
    Route::get('/term', [WebController::class, 'term'])->name('term');
    Route::prefix('member')->middleware(['ensureSignin'])->group(function () {
        Route::get('/', [MemberController::class, 'index'])->middleware(['disableBack'])->name('member');
        Route::get('/upload', [MemberController::class, 'upload'])->name('upload');
        Route::get('/enlarge', [MemberController::class, 'enlarge'])->name('enlarge');
        Route::post('/upload', [MemberController::class, 'storeupload'])->name('storeupload');
        Route::get('/topspender', [MemberController::class, 'topspender'])->name('topspender');
        Route::get('/history', [MemberController::class, 'history'])->name('member.history');
        Route::get('/loadhistory', [MemberController::class, 'loadhistory'])->name('loadhistory');
        Route::get('/profile', [MemberController::class, 'profile'])->name('profile');
        Route::post('/updateprofile', [MemberController::class, 'profilestore'])->name('update.profile');
        Route::get('/instructions', [MemberController::class, 'instructions'])->name('instructions');
        Route::get('/rule', [MemberController::class, 'rule'])->name('rule');
        Route::get('/signout', [MemberController::class, 'signout'])->name('signout');
    });
});
Route::prefix('admin')->middleware(['auth'])->group(function () {
    Route::get('/', [SlipManagerController::class, 'index'])->name('admin.dashboard');
    Route::get('/profile', [ProfileController::class, 'profile'])->name('admin.profile');
    Route::post('/profileupdate', [ProfileController::class, 'store'])->name('admin.profile.store');
    Route::prefix('slips')->group(function () {
        Route::get('/', [SlipManagerController::class, 'index'])->name('admin.slip');
        Route::get('/list', [SlipManagerController::class, 'list'])->name('admin.slip.list');
        Route::post('/store', [SlipManagerController::class, 'store'])->name('admin.slip.store');
        Route::post('/reject', [SlipManagerController::class, 'reject'])->name('admin.slip.reject');
        Route::post('/adminsearchslip', [SlipManagerController::class, 'adminsearchslip'])->name('admin.search.slip');
        Route::get('/verify/{id}', [SlipManagerController::class, 'verify'])->name('admin.slip.verify');
        Route::get('/view/{id}', [SlipManagerController::class, 'viewslip'])->name('admin.slip.view');
    });
    Route::prefix('members')->group(function () {
        Route::get('/', [MemberManagerController::class, 'index'])->name('admin.member');
        Route::get('/topspender', [MemberManagerController::class, 'topspender'])->name('admin.topspender');
        Route::get('/list', [MemberManagerController::class, 'list'])->name('admin.member.list');
        Route::post('/export', [MemberManagerController::class, 'export'])->name('admin.member.export');
        Route::get('/view/{id}', [MemberManagerController::class, 'view'])->name('admin.member.view');
        Route::get('/listslip/{id}', [MemberManagerController::class, 'listslip'])->name('admin.member.slip');
        Route::post('/upload/{id}', [MemberManagerController::class, 'upload'])->name('admin.member.upload');
    });
});
Route::prefix('admins')->middleware(['auth'])->group(function () {
    Route::get('/', function () {
        return redirect('admin');
    });
});
require __DIR__ . '/auth.php';