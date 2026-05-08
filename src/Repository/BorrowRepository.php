<?php
declare(strict_types=1);
namespace App\Repository;

use App\Config\DatabaseConfig;
use App\Config\LibraryConfig;
use App\Service\LibraryService;
use App\Exception\DatabaseException;
use DateTime; 

/**
 * BorrowRepository
 *
 * Handles all database operations related to borrowing and returning books.
 * Acts as the data access layer for borrow_records table.
 *
 * Responsibilities:
 * - Borrow book transactions
 * - Return book processing
 * - Fine computation integration
 *
 * @author lisayAlex
 * @since 2026-05-08
 */
class BorrowRepository
{

    /**
     * @var \PDO Database connection instance
     */
    private $connection;

    /**
     * Initializes repository with database connection.
     *
     * @param DatabaseConfig $database Database configuration instance
     */
    public function __construct(DatabaseConfig $database){
        $this->connection = $database->getConnection();
    }
    
    /**
     * Borrows a book and creates a borrow record.
     *
     * Calculates due date based on number of borrow days,
     * then inserts a new record into borrow_records table.
     *
     * @param int $StudentId ID of the student borrowing the book
     * @param int $bookId ID of the book being borrowed
     * @param int $days Number of days the book will be borrowed
     *
     * @return int Returns the newly created borrow record ID
     *
     * @throws DatabaseException If database insertion fails
     */
    public function borrowBook(int $StudentId, int $bookId, int $days): int{

        /**
         * Compute due date based on borrow duration
         */
        $dueDate = date('Y-m-d', strtotime('+' . $days . ' days'));
        $sql = "INSERT INTO borrow_records(student_id, book_id, borrow_date, due_date, status) 
                VALUES(:student_id, :book_id, :borrow_date, :due_date, :status)";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute([
            'student_id' => $StudentId,
            'book_id' => $bookId,
            'borrow_date' => date('Y-m-d'),
            'due_date' => $dueDate,
            'status' => LibraryConfig::STATUS_BORROWED
        ]);

        return (int) $this->connection->lastInsertId();
    }

    /**
     * Returns a borrowed book and calculates fine if overdue.
     *
     * Process:
     * - Fetch borrow record
     * - Calculate fine using due date
     * - Update record with return details
     * - Commit transaction
     *
     * @param int $recordId Borrow record ID
     *
     * @return float|null Returns computed fine amount or null on failure
     *
     * @throws DatabaseException If return process fails
     */
    public function returnBook(int $recordId): ?float{
        try{
            $this->connection->beginTransaction();
            
            $sql = "SELECT * FROM borrow_records WHERE record_id = :record_id";
            $stmt = $this->connection->prepare($sql);
            $stmt->execute([
                'record_id' => $recordId
            ]);

            $result = $stmt->fetch(\PDO::FETCH_ASSOC);

            /**
             * Calculate overdue fine using service layer
             */
            $fine = LibraryService::calculateOverduefine(
                        new DateTime($result['dueDate']), 
                        LibraryConfig::DAILY_FINE_RATE);

            $sql2 = "UPDATE borrow_records SET return_date = :returned_date, fine_amount = :fine_amount, status = :status WHERE record_id = :record_id";
            $sql2 = $this->connection->prepare($sql2);
            $sql2->execute([
                'returned_date' => date('Y-m-d'),
                'fine_amount' => $fine,
                'status' => LibraryConfig::STATUS_RETURNED,
                'record_id' => $recordId
            ]);

            $this->connection->commit();

            return $fine;

        }catch(\PDOException $error){
            $this->connection->rollBack();
            throw new DatabaseException("Returned Book Failed: " . $error->getMessage());
        }
    }
}

?>