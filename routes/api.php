<?php

use Illuminate\Support\Facades\Route;

// Prima route JSON. Ritornare un array -> Laravel lo converte in JSON automaticamente
Route::get('/ping', function () {
    return ['message' => 'pong'];
});
