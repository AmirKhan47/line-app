<?php

use App\Http\Controllers\LoginController;
use Illuminate\Support\Facades\Route;
// use LINE\LINEBot\HTTPClient\CurlHTTPClient;
use LINE\LINEBot;

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
    return ['Laravel' => app()->version()];
});

Route::get('line/login', [LoginController::class, 'login'])->name('login');
Route::get('callback', [LoginController::class, 'callback'])->name('callback');

Route::get('test_token', [LoginController::class, 'test_token'])->name('test_token');

Route::get('/test', function () {
    $profile = \LINEBot::getProfile($userId);
    return $profile;
});


require __DIR__.'/auth.php';
