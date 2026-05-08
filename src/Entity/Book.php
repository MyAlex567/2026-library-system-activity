<?php
declare(strict_types=1);

namespace App\Entity;
use App\Exception\ValidationException;

/**
 * Book Entity
 * Represents a book record in the library system.
 * Encapsulates book data and ensures validation rules
 * are enforced upon creation.
 *
 * Responsibilities:
 * - Store book information (title, author, year, genre)
 * - Validate publication year
 * - Provide read-only access via getters
 *
 * @author lisayAlex
 * @since 2026-05-08
 */

class Book{
    /**
     * @var int or null Unique identifier of the book (null if not yet saved)
     */
    private ?int $bookId;

    /**
     * @var string Title of the book
     */
    private string $title;

    /**
     * @var string Author of the book
     */
    private string $author;

    /**
     * @var int Publication year of the book
     */
    private int $year;

    /**
     * @var string Genre of the book
     */
    private string $genre;

    /**
     * Book constructor
     *
     * Initializes a Book entity and validates publication year.
     *
     * Validation rules:
     * - Year must not be less than 1000
     * - Year must not be greater than current year
     *
     * @param string $title Book title
     * @param string $author Book author
     * @param int $year Publication year
     * @param string $genre Book genre
     * @param int|null $bookId Optional book ID (for existing records)
     *
     * @throws ValidationException If publication year is invalid
     */
    public function __construct(
        string $title, 
        string $author, 
        int $year, 
        string $genre, 
        ?int $bookId = null
    ){

        if($year < 1000 || $year > (int)date('Y')){
            throw new ValidationException("Invalid Publication Year: " . $year);
        }

        $this->title = $title;
        $this->author = $author;
        $this->year = $year;
        $this->genre = $genre;
        $this->bookId = $bookId;
    }

    /**
     * Get book ID
     *
     * @return int or null Book unique identifier
     */
    public function getBookId(): ?int
    {
        return $this->bookId;
    }

    /**
     * Get book title
     *
     * @return string Book title
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * Get book author
     *
     * @return string Book author
     */
    public function getAuthor(): string
    {
        return $this->author;
    }

    /**
     * Get publication year
     *
     * @return int Publication year
     */
    public function getYear(): int 
    {
        return $this->year;
    }

    /**
     * Get book genre
     *
     * @return string Book genre
     */
    public function getGenre(): string
    {
        return $this->genre;
    }
}

?>