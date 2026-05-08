<?php
declare(strict_types=1);
namespace App\Service;

use App\Config\DatabaseConfig;
use App\Exception\DatabaseException;
use DateTime;
use PDOException;

/**
 * LibraryService
 * 
 * Handles business logic operations for the library system such as:
 * - Calculating overdue fines
 * - Retrieving overdue books
 * 
 * Responsibilities:
 * - Compute overdue penalties
 * - Fetch overdue borrowing records
 * 
 * @author lisayAlex
 * @since 2026-05-08
 */
class LibraryService
{

    /**
     * @var \PDO Database connection instance
     */
    private $connection;


    /**
     * Initializes service with database connection.
     *
     * @param DatabaseConfig $database Database configuration instance
     */
    public function __construct(DatabaseConfig $database){
        $this->connection = $database->getConnection();
    }

    /**
     * Calculates overdue fine based on due date and daily rate.
     * 
     * @param DateTime $dueDate Book due date
     * @param float $dailyRate Fine rate per day
     *
     * @return float Computed fine amount (0 if not overdue)
     */
    public static function calculateOverduefine(DateTime $dueDate, float $dailyRate): float{

        /**
         * Current date reference
         */
        $today = new DateTime();

        /**
         * Difference between due date and today
         */
        $different = $dueDate->diff($today);
        $daysOverdue = (int) $different->format('%r%a');

        return $daysOverdue > 0 ? $daysOverdue * $dailyRate : 0.0;
    }

    /**
     * Retrieves all overdue books from the database.
     * 
     * @return array List of overdue books with student and book details
     *
     * @throws DatabaseException If database query fails
     */
    public function getOverdueBooks(): array{
        try{
            $sql = "SELECT br.*, b.title, s.name 
                    FROM borrow_records br 
                    JOIN books b ON br.book_id = b.book_id 
                    JOIN student s ON br.student_id = s.student_id 
                    WHERE br.due_date < :today AND br.status = 'borrowed'";
            $stmt = $this->connection->prepare($sql);
            $stmt->execute([
                'today' => date('Y-m-d')
            ]);

            return $stmt->fetchAll(\PDO::FETCH_ASSOC);

        }catch(PDOException $error){
            throw new DatabaseException("Failed to retrieve OverDueBooks: " . $error->getMessage());
        }
    }
}

?>