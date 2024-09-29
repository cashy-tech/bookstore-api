<?php

namespace App\Services;

use App\Exceptions\BookException;
use App\Models\Book;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class BookService
{
    public function createBook(array $data): JsonResponse
    {
        try {
            $data['isbn'] = strtoupper($data['isbn']);

            if (Book::where('isbn', $data['isbn'])->exists()) {
                throw BookException::isbnAlreadyTaken();
            }

            $book = Book::create($data);

            if (!$book) {
                throw BookException::createError();
            }

            return response()->json($book, 201);
        } catch (ValidationException $e) {
            throw BookException::validationError();
        } catch (QueryException $e) {
            throw BookException::databaseError();
        }
    }

    public function updateBook(array $data, $id): JsonResponse
    {
        try {
            $book = Book::find($id);

            if (!$book) {
                throw BookException::notAvailable();
            }

            $data['isbn'] = strtoupper($data['isbn']);

            if (Book::where('isbn', $data['isbn'])->exists() && $book->isbn !== $data['isbn']) {
                throw BookException::isbnAlreadyTaken();
            }

            $book->update($data);

            return response()->json($book, 200);
        } catch (ValidationException $e) {
            throw BookException::validationError();
        } catch (QueryException $e) {
            throw BookException::updateError();
        }
    }

    public function getById($id): JsonResponse
    {
        try {
            $book = Book::find($id);

            if (!$book) {
                throw BookException::notAvailable();
            }

            return response()->json($book);
        } catch (QueryException $e) {
            throw BookException::databaseError();
        }
    }

    public function getAll(int $perPage, array $filters = []): JsonResponse
    {
        try {
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

            if ($books->isEmpty()) {
                throw BookException::booksNotFound();
            }

            return response()->json($books);
        } catch (QueryException $e) {
            throw BookException::databaseError();
        }
    }

    public function deleteBook($id): JsonResponse
    {
        try {
            $book = Book::find($id);

            if (!$book) {
                throw BookException::notAvailable();
            }

            $book->delete();
            return response()->json(['message' => 'Book deleted'], 200);
        } catch (QueryException $e) {
            throw BookException::deleteError();
        }
    }
}

