<?php
declare(strict_types=1);

namespace App\Entity;

use App\Config\LibraryConfig;
use DateTime;

/**
 * Borrowed Entity
 * 
 * Represents a borrowing transaction in the library system.
 * Contains information about the student, book, borrow dates,
 * status, and computed fine amount.
 * 
 * Responsibilities
 * - Store borrow transactions data
 * - Tract due and borrow dates
 * - Store cnmputed fine values
 * 
 * @author lisayAlex
 * @since 2026-05-08
 */
class BorrowRecord{

    /**
     * @var int|null Unique ID of the borrow record
     */
    private ?int $recordId;

    /**
     * @var int Student who borrowed the book
     */
    private int $studentId;

    /**
     * @var int Book being borrowed
     */
    private int $bookId;

    /**
     * @var DateTime Date when the book was borrowed
     */
    private DateTime $borrowDate;

    /**
     * @var DateTime Due date for returning the book
     */
    private DateTime $dueDate;

    /**
     * @var string Current status of the borrow record
     */
    private string $status;

    /**
     * @var float Fine amount for overdue books
     */
    private float $fineAmount;

    /**
     * BorrowRecord constructor
     *
     * Initializes a borrow transaction record with default status
     * set to BORROWED if not provided.
     *
     * @param int or null $recordId Optional record ID (for existing entries)
     * @param int $studentId ID of the student who borrowed the book
     * @param int $bookId ID of the borrowed book
     * @param DateTime $borrowDate Date when book was borrowed
     * @param DateTime $dueDate Due date for return
     * @param string $status Borrow status (default: borrowed)
     * @param float $fineAmount Initial fine amount (default: 0.0)
     */
    public function __construct(
        ?int $recordId = null,
        int $studentId,
        int $bookId,
        DateTime $borrowDate,
        DateTime $dueDate,
        string $status = LibraryConfig::STATUS_BORROWED,
        float $fineAmount = 0.0
    )
    {
        $this->recordId = $recordId;
        $this->studentId = $studentId;
        $this->bookId = $bookId;
        $this->borrowDate = $borrowDate;
        $this->dueDate = $dueDate;
        $this->status = $status;
        $this->fineAmount = $fineAmount;
    }

    /**
     * Get borrow record ID
     *
     * @return int or null Record identifier
     */
    public function getRecordId(): ?int
    {
        return $this->recordId;
    }

    /**
     * Get student ID
     *
     * @return int Student identifier
     */
    public function getStudentId(): int
    {
        return $this->studentId;
    }

    /**
     * Get book ID
     *
     * @return int Book identifier
     */
    public function getbookId(): int
    {
        return $this->bookId;
    }

    /**
     * Get borrow date
     *
     * @return DateTime Borrow date
     */
    public function getBorrowDate(): DateTime
    {
        return $this->borrowDate;
    }

    /**
     * Get due date
     *
     * @return DateTime Due date
     */
    public function getDueDate(): DateTime
    {
        return $this->dueDate;
    }

    /**
     * Get borrow status
     *
     * @return string Current status (borrowed/returned)
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * Get fine amount
     *
     * @return float Computed fine amount
     */
    public function getFineAmmount(): float
    {
        return $this->fineAmount;
    }
}

?>