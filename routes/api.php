<?php

use App\Http\Controllers\V1\PostController;
use App\Http\Middleware\EnsureUserApiSession;
use Illuminate\Support\Facades\Route;

Route::middleware([EnsureUserApiSession::class])->prefix('v1')->group(function () {
  Route::get('/me', [PostController::class, 'me']);
});