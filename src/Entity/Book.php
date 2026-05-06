<?php
declare(strict_types=1);

namespace App\Entity;
use App\Exception\ValidationException;

class Book{
    private ?int $bookId;
    private string $title;
    private string $author;
    private int $year;
    private string $genre;

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

    public function getBookId(): ?int
    {
        return $this->bookId;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getAuthor(): string
    {
        return $this->author;
    }

    public function getYear(): int 
    {
        return $this->year;
    }

    public function getGenre(): string
    {
        return $this->genre;
    }
}

?>