<?php

namespace App\Http\Controllers;

use App\Exceptions\BookException;
use App\Services\BookService;
use Illuminate\Http\Request;
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
                'title'   => 'required|string|max:255',
                'author'  => 'required|string|max:255',
                'isbn'    => 'required|string|max:20',
                'price'   => 'required|numeric|min:0',
            ]);

            $book = $this->bookService->createBook($validatedData);

            return response()->json($book, 201);
        } catch (ValidationException $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        } catch (BookException $e) {
            return $e->render();
        }
    }

    public function index()
    {
        try {
            $books = $this->bookService->getAll();
            return response()->json($books, 200);
        } catch (BookException $e) {
            return $e->render();
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $validatedData = $request->validate([
                'title'   => 'sometimes|string',
                'author'  => 'sometimes|string',
                'isbn'    => 'sometimes|string',
                'price'   => 'sometimes|numeric',
            ]);

            $book = $this->bookService->updateBook($validatedData, $id);

            return response()->json($book, 200);
        } catch (ValidationException $e) {
            throw BookException::validationError();
        } catch (BookException $e) {
            return $e->render();
        }
    }

    public function show($id)
    {
        try {
            $book = $this->bookService->getById($id);
            return response()->json($book, 200);
        } catch (BookException $e) {
            return $e->render();
        }
    }

    public function destroy($id)
    {
        try {
            $this->bookService->deleteBook($id);
            return response()->json(['message' => 'Book deleted'], 200);
        } catch (BookException $e) {
            return $e->render();
        }
    }
}
