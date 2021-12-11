<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('multi-agent')
    ->name('multi-agent.')
    ->middleware([])->group(function () {

    });
