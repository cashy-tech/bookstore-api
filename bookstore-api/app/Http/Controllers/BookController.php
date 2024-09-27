<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;

class BookController extends Controller
{
    //Storing a book
 public function store(Request $request)
{
    $validatedData = $request->validate([
        'title' => 'required|string|max:255',
        'author' => 'required|string|max:255',
        'isbn' => 'required|string|unique:books,isbn|max:13',
        'price' => 'required|numeric|min:0',
    ]);

    $book = Book::create($validatedData);

    return response()->json($book, 201);
}


    //Fetching all books
    public function index(){
        $books = Book::all();
        if (!$books){
            return response() -> json(['message' => 'No books found'], 404);
        }
        return response() -> json($books);
    }

    //Fetching a book
    public function show($id){
        $book = Book::find($id);
        if (!$book){
            return response() -> json(['message' => 'Book not found'], 404);
        }
        return response() -> json($book);
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
        $book = Book::find($id);

        if (!$book){
            return response() -> json(['message' => 'Book not found'], 404);
        }

        $book->update($validatedData);
        return response() -> json($book, 200);

    }

    //Deleting a book
    public function destroy($id){
        $book = Book::find($id);
        if (!$book){
            return response() -> json(['message' => 'Book not found'], 404);
        }
        $book->delete();
        return response() -> json(['message' => 'Book deleted'], 200);
    }

}
