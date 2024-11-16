<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\FaceController;

Route::middleware('api')->group(function () {
    Route::post('/check-face-match', [FaceController::class, 'checkFaceMatch']);
});