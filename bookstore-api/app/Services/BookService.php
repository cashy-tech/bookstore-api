<?php

namespace App\Services;

use App\Exceptions\BookException;
use App\Models\Book;
use Illuminate\Database\QueryException;
use Illuminate\Validation\ValidationException;

class BookService
{
   public function createBook(array $data)
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

        return $book;
    } catch (ValidationException $e) {
        throw BookException::validationError();
    } catch (QueryException $e) {
        throw BookException::databaseError();
    }
}


    public function updateBook(array $data, $id)
    {
        try {

            $book = Book::find($id);

            if (!$book) {
                throw BookException::notAvailable();
            }

            $data['isbn'] = strtoupper($data['isbn']);

            if (Book::where('isbn', $data['isbn'])->exists()) {
                throw BookException::isbnAlreadyTaken();
            }

            $book->update($data);

            return $book;
        } catch (ValidationException $e) {
            throw BookException::validationError();
        } catch (QueryException $e) {
            throw BookException::updateError();
        }
    }

    public function getById($id)
    {
        try {
            $book = Book::find($id);

            if (!$book) {
                throw BookException::notAvailable();
            }

            return $book;
        } catch (QueryException $e) {
            throw BookException::databaseError();
        }
    }

    public function getAll()
    {
        try {
            $books = Book::all();

            if ($books->isEmpty()) {
                throw BookException::booksNotFound();
            }

            return $books;
        } catch (QueryException $e) {
            throw BookException::databaseError();
        }
    }

    public function deleteBook($id)
    {
        try {
            $book = Book::find($id);

            if (!$book) {
                throw BookException::notAvailable();
            }

            $book->delete();
        } catch (QueryException $e) {
            throw BookException::deleteError();
        }
    }
}

