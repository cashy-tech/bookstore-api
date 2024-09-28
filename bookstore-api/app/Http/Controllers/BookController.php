<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;
use App\Services\BookService;

class BookController extends Controller
{
    protected $bookService;
    public function __construct(BookService $bookService)
    {
        $this->bookService = $bookService;
    }

    //Storing a book
 public function store(Request $request)
{
    $validatedData = $request->validate([
        'title' => 'required|string|max:255',
        'author' => 'required|string|max:255',
        'isbn' => 'required|string|unique:books,isbn|max:13',
        'price' => 'required|numeric|min:0',
    ]);

    $book = $this->bookService->createBook($validatedData);

    return response()->json($book, 201);

}


//Updating a book
public function update(Request $request, $id)
{
    $validatedData = $request->validate([
        'title' => 'sometimes|string',
        'author' => 'sometimes|string',
        'isbn' => 'sometimes|string',
        'price' => 'sometimes|numeric',
    ]);
    $book = $this->bookService->updateBook($validatedData, $id);
    return response()->json($book, 200);

}
//Fetching a book
public function show($id){
    $book = $this->bookService->getById($id);

    return response()->json($book);
}
    //Fetching all books
    public function index(){

        $books = $this->bookService->getAll();
        return response()->json($books);
    }


    //Deleting a book
    public function destroy($id){
        $book = $this->bookService->deleteBook($id);
        return response()->json(['message' => 'Book deleted'], 200);
    }

}

