<?php

use App\Http\Controllers\AzureFileController;
use Illuminate\Support\Facades\Route;

Route::get('/', [AzureFileController::class, 'index']);
Route::post('/fichiers/upload', [AzureFileController::class, 'upload'])->name('azure.upload');
Route::delete('/fichiers/{nom}', [AzureFileController::class, 'destroy'])->name('azure.destroy');


