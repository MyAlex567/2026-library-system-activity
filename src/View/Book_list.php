<?php
declare(strict_types=1);
require_once __DIR__ . '../../../vendor/autoload.php';

use App\Repository\BookRepository;
use App\Config\DatabaseConfig;
use App\Exception\DatabaseException;

/**
 * Book List Page
 *
 * Displays all books stored in the library database.
 * Acts as a simple presentation layer that retrieves data
 * from the BookRepository and renders it in an HTML table.
 *
 * Responsibilities:
 * - Fetch list of books from repository
 * - Handle empty state display
 * - Render book data in table format
 *
 * @author lisayAlex
 * @since 2026-05-08
 */

$database = new DatabaseConfig();
$bookrepo = new BookRepository($database);


/**
 * @var array List of books retrieved from database
 */
$bookList = [];

try{
    $bookList = $bookrepo->listBooks();
}catch(DatabaseException $error){
    /**
     * Optional: log error or handle gracefully
     * Still no idea here
     */
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BOOK LIST</title>

    <style>
        body{
            display: flex;
            flex-direction: column;
            align-items: center;
            font-family: Arial, Helvetica, sans-serif;
        }

        .BookList-header{
            background-color: #4ccd6a;
            border-radius: 5px;
            padding: 10px;
            width: 800px;
            text-align: center;
        }

        .bookList{
            border-collapse: collapse;
            width: 800px;
        }

        .bookList tr:nth-child(even){
            background-color: #f2f2f2;
        }

        .bookList tr:nth-child(odd){
            background-color: #ffffff;
        }

        .bookList tr td{
            padding: 10px;
            font-size: 1.2rem;
            text-align: center;
        }

    </style>

</head>
<body>

    <h3 class="BookList-header">Book List</h3>

    <table class="bookList">
        <tr>
            <th>BookId</th>
            <th>Book Title</th>
            <th>Book Author</th>
            <th>Book Year</th>
            <th>Genre</th>
        </tr>

        <?php if(empty($bookList)): ?>
            <p>No book List Found</p>
        <?php else: ?>
            <?php foreach($bookList as $book): ?>
                <tr>
                    <td><?php echo $book['book_id'] ?></td>
                    <td><?php echo $book['title'] ?></td>
                    <td><?php echo $book['author'] ?></td>
                    <td><?php echo $book['year'] ?></td>
                    <td><?php echo $book['genre'] ?></td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>

    </table>
    
</body>
</html>