<?php

use App\Http\Controllers\MemberController;

Route::get('/members', [MemberController::class, 'index']);