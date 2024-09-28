<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\Book;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class BookService
{

    public function createBook(array $data): JsonResponse
    {
        $book = Book::create($data);

        if (!$book) {
            return response()->json(['message' => 'Book not created'], 500);
        }

        return response()->json($book, 201);
    }

    public function updateBook(array $data, $id): JsonResponse
    {

        $book = Book::find($id);

        if (!$book) {
            return response()->json(['message' => 'Book not found'], 404);
        }

        $book->update($data);
        return response()->json($book, 200);
    }

    public function getById($id): JsonResponse
    {
        $book = Book::find($id);
        if (!$book) {
            return response()->json(['message' => 'Book not found'], 404);
        }
        return response()->json($book);
    }

    public function getAll(): JsonResponse
    {
        $books = Book::all();
        if (!$books) {
            return response()->json(['message' => 'No books found'], 404);
        }
        return response()->json($books);
    }

    public function deleteBook($id): JsonResponse
    {
        $book = Book::find($id);

        if (!$book) {
            throw new ModelNotFoundException("Book not found");        }

        $book->delete();

        return response()->json(['message' => 'Book deleted'], 200);
    }
}

