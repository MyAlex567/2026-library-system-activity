<?php
declare(strict_types=1);

namespace App\Repository;

use App\Config\DatabaseConfig;
use App\Entity\Book;
use App\Exception\DatabaseException;

/**
 * BookRepository
 *
 * Handles all database operations related to Book entities.
 * Acts as the data access layer between the application and the database.
 *
 * Responsibilities:
 * - Insert new books
 * - Retrieve single book records
 * - Search books by keyword
 * - List all books
 *
 * @author lisayAlex
 * @since 2026-05-08
 */
class BookRepository{

    /**
     * @var \PDO Database connection instance
     */
    private $connection;

    /**
     * Initializes repository with database connection.
     *
     * @param DatabaseConfig $database Database configuration object
     */
    public function __construct(DatabaseConfig $database)
    {
        $this->connection = $database->getConnection();
    }

    /**
     * Inserts a new book into the database.
     *
     * @param Book $book Book entity to be stored
     *
     * @return int Returns the newly generated book ID
     *
     * @throws DatabaseException If database operation fails
     */
    public function addBook(Book $book): ?int
    {
        
        $sql = "INSERT INTO books(title,author,year,genre) VALUES(:title, :author, :year, :genre)";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute([
            'title' => $book->getTitle(),
            'author' => $book->getAuthor(),
            'year' => $book->getYear(),
            'genre' => $book->getGenre()
        ]);

        return (int) $this->connection->lastInsertId();
    }

    /**
     * Retrieves a single book by ID.
     *
     * @param int $bookId Book identifier
     *
     * @return Book Returns Book entity
     *
     * @throws DatabaseException If query fails or book not found
     */
    public function getBook(int $bookId): Book
    {
        $sql = "SELECT * FROM books WHERE bookId = :bookId";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute([
            'bookId' => $bookId
        ]);

        $result = $stmt->fetch(\PDO::FETCH_ASSOC);

        $book = new Book(
                    $result['title'], 
                    $result['author'], 
                    $result['year'],
                    $result['genre'],
                    $result['book_id']
                    );

        return $book;
    }

    /**
     * Searches books by title or author keyword.
     *
     * @param string $keyword Search term
     *
     * @return Book[] Array of matching Book entities
     *
     * @throws DatabaseException If query fails
     */
    public function searchBooks(string $keyword): array
    {
        try{
            $sql = "SELECT * FROM books WHERE title LIKE :keyword OR author LIKE :keyword";
            $stmt = $this->connection->prepare($sql);
            $stmt->execute([
                'keyword' => '%' . $keyword . '%'
            ]);
            $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            $books = [];
            foreach($result as $value){
                $books[] = new Book(
                    $value['title'], 
                    $value['author'], 
                    $value['year'],
                    $value['genre'],
                    $value['book_id']
                );
            }


            return $books;

        }catch(\PDOException $error){
            throw new DatabaseException("Search Failed: " . $error->getMessage());
        }
    }

    /**
     * Retrieves all books from the database.
     *
     * @return array List of books as associative arrays
     *
     * @throws DatabaseException If query fails
     */
    public function listBooks(): array
    {
        try{
            $sql = "SELECT * FROM books";
            $stmt = $this->connection->prepare($sql);
            $stmt->execute();

            return $stmt->fetchAll(\PDO::FETCH_ASSOC);

        }catch(\PDOException $error){
            throw new DatabaseException("Failed to fetch the books: " . $error->getMessage());
        }
    }


}

?>