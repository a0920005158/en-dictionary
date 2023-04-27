<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SearchWordController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\AIConversationController;

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

Route::match(['get', 'post'], '/test', [TestController::class, 'test']);

// Route::match(['get', 'post'], '/api/ai-conversation', [AIConversationController::class, 'sendMsg']);

// Route::post( '/ai-conversation', [AIConversationController::class, 'sendMsg']);
// Route::get('/search',[SearchWordController::class,'searchWord']);
