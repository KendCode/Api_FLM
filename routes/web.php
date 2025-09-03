<?php

use Illuminate\Support\Facades\Route;

Route::get('/api-docs.json', function () {
    $path = storage_path('api-docs/api-docs.json');

    abort_unless(file_exists($path), 404, 'api-docs.json no encontrado');

    return response()->file($path, [
        'Content-Type' => 'application/json; charset=utf-8'
    ]);
});

