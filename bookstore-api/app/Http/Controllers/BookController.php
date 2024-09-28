<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;
use App\Services\BookService;
use Illuminate\Database\Eloquent\ModelNotFoundException;

use Illuminate\Validation\ValidationException;

class BookController extends Controller
{
    protected $bookService;

    public function __construct(BookService $bookService)
    {
        $this->bookService = $bookService;
    }

    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'title' => 'required|string|max:255',
                'author' => 'required|string|max:255',
                'isbn' => 'required|string|unique:books,isbn|max:13',
                'price' => 'required|numeric|min:0',
            ], [
                'title.required' => 'The title field is required.',
                'author.required' => 'The author field is required.',
                'isbn.required' => 'The ISBN field is required.',
                'price.required' => 'The price field is required.',
            ]);

            $book = $this->bookService->createBook($validatedData);

            return response()->json($book, 201);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation errors',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function index(Request $request)
    {
        $request->validate([
            'per_page' => 'integer|min:1',
            'author' => 'nullable|string|max:255',
            'min_price' => 'nullable|numeric',
            'max_price' => 'nullable|numeric',
        ]);

        $perPage = $request->input('per_page', 10);

        $filters = [
            'author' => $request->input('author'),
            'min_price' => $request->input('min_price'),
            'max_price' => $request->input('max_price'),
        ];

        $books = $this->bookService->getAll((int)$perPage, $filters);

        return response()->json($books, 200);
    }

    public function update(Request $request, $id)
    {
        try {
            $validatedData = $request->validate([
                'title' => 'sometimes|string',
                'author' => 'sometimes|string',
                'isbn' => 'sometimes|string',
                'price' => 'sometimes|numeric',
            ]);

            $book = $this->bookService->updateBook($validatedData, $id);

            return response()->json($book, 200);
        } catch (ValidationException $e) {
            return response()->json(['error' => $e->validator->errors()], 422);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Book not found'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred while updating the book'], 500);
        }
    }

    public function show($id)
    {
        try {
            $book = $this->bookService->getById($id);

            return response()->json($book, 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Book not found'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred while fetching the book'], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $this->bookService->deleteBook($id);

            return response()->json(['message' => 'Book deleted'], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Book not found'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred while deleting the book'], 500);
        }
    }
}
