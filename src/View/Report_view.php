<?php
declare(strict_types=1);
require_once __DIR__ . '../../../vendor/autoload.php';

use App\Service\LibraryReport;
use App\Config\DatabaseConfig;
use App\Config\LibraryConfig;
use App\Service\LibraryService;

$database = new DatabaseConfig();
$LibraryReport = new LibraryReport($database);
$libraryService = new LibraryService($database);

$reports = [];
$overDueBooks = [];

try{
    $reports = $LibraryReport->generateReport();
    $overDueBooks = $libraryService->getOverdueBooks();

    for($index = 0; $index < count($overDueBooks); $index++){
        $overDueBooks[$index]['fine_amount'] = $overDueBooks[$index]['fine_amount'] ?? 
                                            $libraryService->calculateOverduefine(
                                            new DateTime($overDueBooks[$index]['due_date']), LibraryConfig::DAILY_FINE_RATE);
    }

}catch(PDOException){

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>REPORT VIEW</title>

    <style>
        body{
            display: flex;
            flex-direction: column;
            align-items: center;
            font-family: Arial, Helvetica, sans-serif;
        }

        .report-container{
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .report-table{
            border-collapse: collapse;
            text-align: center;
            font-size: 1.2rem;
        }

        .report-table tr td, tr th{
            padding: 10px;
        }

        .report-table tr:nth-child(even){
            background-color: #f2f2f2;
        }

        .report-table tr:nth-child(odd){
            background-color: #ffffff;
        }

    </style>

</head>
<body>

    <div class="report-container">
        <h1 style="text-align: center;">Library Report</h1>
        <table class="report-table">
            <tr>
                <th>Category</th>
                <th>Data</th>
            </tr>

            <tr>
                <td>Total Books:</td>
                <td><?php echo $reports ? $reports['totalBooks'] : 'No available Data' ?></td>
            </tr>

            <tr>
                <td>Total Borrowed:</td>
                <td><?php echo $reports ? $reports['totalBorrowed'] : 'No available Data' ?></td>
            </tr>

            <tr>
                <td>Total Returned:</td>
                <td><?php echo $reports ? $reports['totalReturned'] : 'No available Data' ?></td>
            </tr>

            <tr>
                <td>Total Fines:</td>
                <td><?php echo $reports ? $reports['totalFines'] : 'No available Data' ?></td>
            </tr>
        </table>

        <h1>OVERDUE BOOKS</h1>

        <table class="report-table">
            <tr>
                <th>Record ID</th>
                <th>Student ID</th>
                <th>Book ID</th>
                <th>Student Name</th>
                <th>Book Title</th>
                <th>Borrow Date</th>
                <th>Return Date</th>
                <th>Due Date</th>
                <th>Fine Amount</th>
                <th>Status</th>
            </tr>

            <?php if(empty($overDueBooks)): ?>
                <tr>
                    <td>No Over Due Books</td>
                </tr>
            <?php else: ?>
                <?php for($index = 0; $index < count($overDueBooks); $index++): ?>
                    <tr>
                        <td><?php echo $overDueBooks[$index]['record_id'] ?></td>
                        <td><?php echo $overDueBooks[$index]['student_id'] ?></td>
                        <td><?php echo $overDueBooks[$index]['book_id'] ?></td>
                        <td><?php echo $overDueBooks[$index]['name'] ?></td>
                        <td><?php echo $overDueBooks[$index]['title'] ?></td>
                        <td><?php echo $overDueBooks[$index]['borrow_date'] ?></td>
                        <td><?php echo $overDueBooks[$index]['return_date'] ?></td>
                        <td><?php echo $overDueBooks[$index]['due_date'] ?></td>
                        <td><?php echo $overDueBooks[$index]['fine_amount'] ?></td>
                        <td><?php echo $overDueBooks[$index]['status'] ?></td>
                    </tr>
                <?php endfor; ?>
            <?php endif; ?>
            


        </table>

    </div>


    
</body>
</html>