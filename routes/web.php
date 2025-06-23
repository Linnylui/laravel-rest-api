<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('layouts/dashboard');
});
Route::get('/mahasiswa', function () {
    return view('layouts/mahasiswa');
})->name('mahasiswa');
