<?php
declare(strict_types=1);
use App\Config\DatabaseConfig;
use App\Config\LibraryConfig;
use App\Service\LibraryService;
use App\Exception\DatabaseException;

class BorrowRepository{
    private $connection;

    public function __construct(DatabaseConfig $database){
        $this->connection = $database->getConnection();
    }
    
    public function borrowBook(int $StudentId, int $bookId, int $days): int{
        $dueDate = date('Y-m-d', strtotime('+' . $days . ' days'));
        $sql = "INSERT INTO borrow_records(studentId, bookId, borrowDate, dueDate, status) 
                VALUES(:studentId, :bookId, :borrowDate, :dueDate, :status)";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute([
            'studentId' => $StudentId,
            'bookId' => $bookId,
            'borrowDate' => date('Y-m-d'),
            'dueDate' => $dueDate,
            'status' => 'borrowed'
        ]);

        return (int) $this->connection->lastInsertId();
    }

    public function returnBook(int $recordId): ?float{
        try{
            $this->connection->beginTransaction();
            
            $sql = "SELECT * FROM borrowRecords WHERE recordId = :recordId";
            $stmt = $this->connection->prepare($sql);
            $stmt->execute([
                'recordId' => $recordId
            ]);

            $result = $stmt->fetch(\PDO::FETCH_ASSOC);
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