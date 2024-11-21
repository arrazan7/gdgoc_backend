<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/**
 * @OA\Info(
 *    description="API Documentation for Book Management",
 *    version="1.0.0",
 *    title="Book Management API",
 *    termsOfService="http://swagger.io/terms/",
 *    @OA\Contact(
 *        email="fayyadharrazanmiftakhul@mail.ugm.ac.id"
 *    ),
 *    @OA\License(
 *        name="Apache 2.0",
 *        url="http://www.apache.org/licenses/LICENSE-2.0.html"
 *    )
 * )
 */

class BookAPIController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/books",
     *     summary="Get all books",
     *     operationId="getBooks",
     *     tags={"Books"},
     *     @OA\Response(
     *         response=200,
     *         description="List of books retrieved successfully"
     *     )
     * )
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     * 
     * @OA\Post(
     *     path="/api/books",
     *     summary="Create a new book",
     *     operationId="createBook",
     *     tags={"Books"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="title", type="string", example="Example Book"),
     *             @OA\Property(property="author", type="string", example="John Doe"),
     *             @OA\Property(property="published_at", type="string", format="date", example="2023-11-21")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Book created successfully"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation failed"
     *     )
     * )
     */
    public function store(Request $request)
    {
        // Validasi data
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'published_at' => 'required|date',
        ]);

        // Jika validasi gagal
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            // Simpan data ke database
            $book = Book::create($request->all());

            return response()->json([
                'success' => true,
                'message' => 'Book created successfully',
                'data' => $book,
            ], 201);
        } catch (\Exception $e) {
            // Jika ada error saat menyimpan
            return response()->json([
                'success' => false,
                'message' => 'Failed to create book',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     * 
     * @OA\Get(
     *     path="/api/books/{id}",
     *     summary="Get a book by ID",
     *     operationId="getBookById",
     *     tags={"Books"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Book retrieved successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Book not found"
     *     )
     * )
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     * 
     * @OA\Put(
     *     path="/api/books/{id}",
     *     summary="Update a book by ID",
     *     operationId="updateBook",
     *     tags={"Books"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="title", type="string", example="Updated Book"),
     *             @OA\Property(property="author", type="string", example="Jane Doe"),
     *             @OA\Property(property="published_at", type="string", format="date", example="2023-11-21")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Book updated successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Book not found"
     *     )
     * )
     */
    public function update(Request $request, $id)
    {
        // Validasi data
        $validator = Validator::make($request->all(), [
            'title' => 'sometimes|required|string|max:255', // atribut bersifat opsional menggunakan sometimes
            'author' => 'sometimes|required|string|max:255',
            'published_at' => 'sometimes|required|date',
        ]);

        // Jika validasi gagal
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            // Cari buku berdasarkan ID
            $book = Book::findOrFail($id);

            // Update data
            $book->update($request->all());

            return response()->json([
                'success' => true,
                'message' => 'Book updated successfully',
                'data' => $book,
            ], 200);
        } catch (\Exception $e) {
            // Jika ada error saat update
            return response()->json([
                'success' => false,
                'message' => 'Failed to update book',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     * 
     * @OA\Delete(
     *     path="/api/books/{id}",
     *     summary="Delete a book by ID",
     *     operationId="deleteBook",
     *     tags={"Books"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Book deleted successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Book not found"
     *     )
     * )
     */
    public function destroy($id)
    {
        try {
            // Cari buku berdasarkan ID
            $book = Book::findOrFail($id);

            // Hapus data
            $book->delete();

            return response()->json([
                'success' => true,
                'message' => 'Book deleted successfully',
            ], 200);
        } catch (\Exception $e) {
            // Jika ada error saat menghapus
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete book',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
