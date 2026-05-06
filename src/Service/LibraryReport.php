<?php
declare(strict_types=1);
namespace App\Service;

use App\Config\DatabaseConfig;
use App\Exception\DatabaseException;

class LibraryReport{
    private $connection;

    public function __construct(DatabaseConfig $database)
    {
        $this->connection = $database->getConnection();
    }

    public function generateReport(): array{
        try{
            $report = [];

            $sql1 = 'SELECT COUNT(*) FROM books';
            $stmt1 = $this->connection->prepare($sql1);
            $stmt1->execute();
            $report['totalBooks'] = $stmt1->fetchColumn();

            $sql2 = "SELECT COUNT(*) FROM borrow_records WHERE status='borrowed'";
            $stmt2 = $this->connection->prepare($sql2);
            $stmt2->execute();
            $report['totalBorrowed'] = $stmt2->fetchColumn();

            $sql3 = "SELECT COUNT(*) FROM borrow_records WHERE status='returned'";
            $stmt3 = $this->connection->prepare($sql3);
            $stmt3->execute();
            $report['totalReturned'] = $stmt3->fetchColumn();

            $sql4 = "SELECT SUM(fine_amount) FROM borrow_records WHERE fine_amount > 0";
            $stmt4 = $this->connection->prepare($sql4);
            $stmt4->execute();
            $report['totalFines'] = $stmt4->fetchColumn() ?? 0;

            return $report;

        }catch(\PDOException $error){
            throw new DatabaseException("Failed to Generate Report: " . $error->getMessage());
        }
    }
}


?>