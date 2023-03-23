<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SearchWordController;
use App\Http\Controllers\AIConversationController;
use App\Http\Controllers\AITravelPlanController;
use App\Http\Controllers\LoginController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

// Route::middleware('auth:api-user')->get('/search-word', [SearchWordController::class, 'searchWord']);
// Route::middleware('auth:api-user')->get('/ai-coversation', [AIConversationController::class, 'sendMsg']);

Route::match(['get', 'post'], '/search-word', [SearchWordController::class, 'searchWord']);
// Route::match(['get', 'post'], '/ai-conversation', [AIConversationController::class, 'sendMsg']);
Route::post( '/ai-conversation', [AIConversationController::class, 'sendMsg']);
Route::post( '/travel-plan', [AITravelPlanController::class, 'plan']);
Route::get('/login', [LoginController::class, 'AuthIdentity']);
