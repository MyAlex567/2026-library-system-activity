<?php
declare(strict_types=1);

use App\Config\DatabaseConfig;
use App\Entity\Book;
use App\Exception\DatabaseException;

class BookRepository{
    private $connection;

    public function __construct(DatabaseConfig $database){
        $this->connection = $database->getConnection();
    }

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