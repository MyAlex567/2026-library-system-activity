<?php
declare(strict_types=1);
namespace App\Service;

use App\Config\DatabaseConfig;
use App\Exception\DatabaseException;
use DateTime;
use PDOException;

class LibraryService{
    private $connection;

    public function __construct(DatabaseConfig $database){
        $this->connection = $database->getConnection();
    }

    public static function calculateOverduefine(DateTime $dueDate, float $dailyRate): float{
        $today = new DateTime();
        $different = $dueDate->diff($today);
        $daysOverdue = (int) $different->format('%r%a');

        return $daysOverdue > 0 ? $daysOverdue * $dailyRate : 0.0;
    }

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