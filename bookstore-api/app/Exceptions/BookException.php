<?php

namespace App\Exceptions;

class BookException extends CustomException
{
    public static function notAvailable(): BookException
    {
        return new self('Book not available', 404);
    }

    public static function isbnAlreadyTaken(): BookException
    {
        return new self('ISBN already taken', 409);
    }

    public static function booksNotFound(): BookException
    {
        return new self('Books not found', 404);
    }

    public static function deleteError(): BookException
    {
        return new self('An error occurred while deleting the book', 500);
    }

    public static function updateError(): BookException
    {
        return new self('An error occurred while updating the book', 500);
    }

    public static function createError(): BookException
    {
        return new self('An error occurred while creating the book', 500);
    }

    public static function validationError(): BookException
    {
        return new self('Validation error', 422);
    }

    public static function databaseError(): BookException
    {
        return new self('Database connection error', 500);
    }

    public static function unexpectedError($message = 'An unexpected error occurred'): BookException
    {
        return new self($message, 500);
    }
}
