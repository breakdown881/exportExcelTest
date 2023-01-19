<?php

use App\Http\Controllers\Api\ExportAddressController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::get('/export-addresses-employer', [ExportAddressController::class, 'ExportAddressFromDatabase']);
Route::get('/export-addresses-file', [ExportAddressController::class, 'ExportAddressByFile']);