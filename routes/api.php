<?php

use App\Http\Controllers\CreditAnalysisController;
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

Route::get('/analise-credito', [CreditAnalysisController::class, 'index']);
Route::post('/analise-credito', [CreditAnalysisController::class, 'creditAnalysis']);
Route::post('/analise-credito/consulta', [CreditAnalysisController::class, 'showAnalysis']);
Route::put('/analise-credito/{id}', [CreditAnalysisController::class, 'update']);
Route::delete('/analise-credito/{id}', [CreditAnalysisController::class, 'destroy']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});