<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Controllers\BookAPIController;
use App\Http\Controllers\GreetController;
use App\Http\Resources\BookCollection;
use App\Http\Resources\BookResource;
use App\Models\Book;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/books', function () {
    return new BookCollection(Book::all());
});
Route::get('/books/{id}', function (string $id) {
    try {
        $book = Book::findOrFail($id);
        return new BookResource($book);
    } catch (ModelNotFoundException $e) {
        return response()->json([
            'message' => 'Book not found',
        ], 404); // Mengembalikan respons JSON dengan status 404
    }
});
Route::post('/books', [BookAPIController::class, 'store']);
Route::put('/books/{id}', [BookAPIController::class, 'update']);
Route::delete('/books/{id}', [BookAPIController::class, 'destroy']);