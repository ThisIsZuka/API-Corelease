<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\LocalService\ConvertIMG_File;


/*
|--------------------------------------------------------------------------
| Local Routes
|--------------------------------------------------------------------------
|
| local routes for process run on local 
|
*/


Route::get('/aaa', function () {
    return 'Welcome to the local route!';
});

Route::any('/ConvertFile', [ConvertIMG_File::class, 'ConvertFile']);

// You can add more routes as needed
