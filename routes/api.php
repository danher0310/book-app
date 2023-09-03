<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthorController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\GenreController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\NoteAuthorController;
use App\Http\Controllers\NoteBookController;
use App\Http\Controllers\PublisherController;
use App\Http\Controllers\RaitingAuthorController;
use App\Http\Controllers\RaitingBookController;
use App\Models\Publisher;

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

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware(['auth:sanctum'])->group(function(){
    

});

Route::controller(AuthorController::class)->group(function(){
    Route::get('/authors', 'index');
    Route::post('/authors', 'store');
    Route::get('/authors/{id}', 'show');
    Route::put('/authors/{id}', 'update');
    Route::delete('authors/{id}','destroy');
});



Route::controller(ProfileController::class)->group(function(){
    
    Route::post('/profiles', 'store');    
    Route::put('/profiles/{id}', 'update');
    
});

Route::controller(NoteAuthorController::class)->group(function(){
    Route::get('/authors/{id}/notes', 'index');
    Route::post('/authors/notes', 'store');
    Route::put('/authors/notes/{id}', 'update');
    Route::delete('/authors/notes/{id}', 'destroy');

});

Route::controller(NoteBookController::class)->group(function(){
    Route::get('/book/{id}/notes', 'index');
    Route::post('/book/notes', 'store');
    Route::put('/book/notes/{id}', 'update');
    Route::delete('book/notes/{id}', 'destroy');
});

Route::controller(RaitingAuthorController::class)->group(function(){
    Route::post('/authors/raitings', 'store');
    Route::get('/authors/{id}/raitings', 'show');
    Route::put('/authors/raiting/{id}', 'update');
});

Route::controller(RaitingBookController::class)->group(function(){
    Route::post('/books/raitings', 'store');
    Route::get('/book/{id}/raiting', 'show');
    Route::put('/book/raiting/{id}', 'update');


});

Route::controller(GenreController::class)->group(function (){
    Route::get('/genre', 'index');
    Route::get('/genre/{id}', 'show');
    Route::post('/genre', 'store');
    Route::put('/genre/{id}', 'update');
    Route::delete('/genre/{id}', 'destroy');

});

Route::controller(PublisherController::class)->group(function(){
    Route::get('/publisher', 'index');
    Route::get('/publisher/{id}', 'show');
    Route::post('/publisher', 'store');
    Route::put('/publisher/{id}', 'update');
    Route::delete('/publisher/{id}', 'destroy');


});

Route::controller(BookController::class)->group(function(){
    Route::get('/books','index');
    Route::get('/book/{id}', 'show');
    Route::post('/book', 'store');
    Route::put('/book/{id}', 'update');
    Route::delete('/book/{id}', 'destroy');


});