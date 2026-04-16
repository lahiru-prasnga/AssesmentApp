<?php

use Illuminate\Support\Facades\Route;
use Modules\AssessmentUpload\Http\Controllers\AssessmentController;
use Modules\AssessmentUpload\Http\Controllers\AssessmentFileController;

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

Route::prefix('assessments')->group(function () {
    Route::get('/', [AssessmentController::class, 'index']);
    Route::post('/', [AssessmentController::class, 'store']);
    Route::get('/{id}', [AssessmentController::class, 'show']);
    Route::post('/{id}/files', [AssessmentFileController::class, 'upload']);
    Route::delete('/{id}/files/{fileId}', [AssessmentFileController::class, 'destroy']);
    Route::post('/{id}/submit', [AssessmentController::class, 'submit']);
});