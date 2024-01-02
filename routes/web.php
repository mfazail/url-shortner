<?php

use App\Http\Controllers\UrlController;
use Illuminate\Http\Request;
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

Route::redirect('/','/dashboard');

// Route::get('/request', function (Request $request) {
//     return $request->headers;
// });

// Catch all routes
Route::get('/{url}', [UrlController::class,'show'])
    // only catch urls that are alphanumeric and include - and _ characters
    ->where('url', '[A-Za-z0-9-\-\_]+');
    // else through 404 not found
