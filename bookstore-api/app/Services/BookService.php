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

    public function getAll(int $perPage, array $filters = []): JsonResponse
    {
        $query = Book::query();

        if (isset($filters['author'])) {
            $author = $filters['author'];
            $query->where('author', 'like', '%' . $author . '%');
        }

        if (isset($filters['min_price']) && isset($filters['max_price'])) {
            $minPrice = (float)$filters['min_price'];
            $maxPrice = (float)$filters['max_price'];

            if (is_numeric($minPrice) && is_numeric($maxPrice) && $minPrice <= $maxPrice) {
                $query->whereBetween('price', [$minPrice, $maxPrice]);
            }
        }

        $books = $query->paginate($perPage);

        return response()->json($books);
    }

    public function deleteBook($id): JsonResponse
    {
        $book = Book::find($id);

        if (!$book) {
            throw new ModelNotFoundException("Book not found");
        }

        $book->delete();

        return response()->json(['message' => 'Book deleted'], 200);
    }
}

