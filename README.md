# Bookstore API

This is a RESTful API for a bookstore. It's built with Laravel and uses a MySQL database.

## Endpoints

### GET /books

Returns a list of all books in the database.

### GET /books/{id}

Returns the book with the specified ID.

### POST /books

Creates a new book in the database.

### PATCH /books/{id}

Updates the book with the specified ID.

### DELETE /books/{id}

Deletes the book with the specified ID.

## Running the application locally

To run the application locally follow the following steps:

1. Run `composer install` to install all of the dependencies.

2. Create a MySQL database 

3. Create a `.env` file from `.env example`

4. Update the Database variables in the `.env` file to match your database setup (make sure to use the correct database name, username and password).

5. Run `php artisan migrate` to create the tables in the database.

6. Run `php artisan serve` to start the development server.

