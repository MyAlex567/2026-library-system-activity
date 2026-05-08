<?php
declare(strict_types=1);
session_start();
require_once __DIR__ . '../../../vendor/autoload.php';

use App\Repository\BorrowRepository;
use App\Config\DatabaseConfig;

/**
 * Borrow Book Page
 *
 * Handles the borrowing process of books in the library system.
 * This page acts as a controller + view hybrid that:
 * - Accepts user input (student ID, book ID, borrow days)
 * - Calls BorrowRepository to process borrowing
 * - Displays success or error messages using session flash data
 *
 * Responsibilities:
 * - Process borrow requests
 * - Validate input (basic casting)
 * - Display feedback messages
 *
 * @author lisayAlex
 * @since 2026-05-08
 */

$database = new DatabaseConfig();
$borrowrepo = new BorrowRepository($database);

/**
 * @var string Message to display to user
 */
$message = '';

/**
 * @var string Message type (success/error)
 */
$messageType = '';

if(isset($_POST['borrowBook']) && $_SERVER['REQUEST_METHOD'] === 'POST'){
    try{
        $borrowData = [
            'student_id' => (int)$_POST['student_id'],
            'book_id' => (int)$_POST['book_id'],
            'borrow_days' => (int)$_POST['borrow_days'],
        ];

        $result = $borrowrepo->borrowBook($borrowData['student_id'], $borrowData['book_id'], $borrowData['borrow_days']);


        if(isset($result)){
            $_SESSION['message'] = 'Borrow Success';
            $_SESSION['messageType'] = 'success';
        }else{
            $_SESSION['message'] = 'Failed to borrow';
            $_SESSION['messageType'] = 'error';     
        }
    }catch(PDOException){
        $_SESSION['message'] = 'Failed to borrow';
        $_SESSION['messageType'] = 'error';   
    }

    header('Location: ' . $_SERVER['PHP_SELF']);
    exit();
}

/**
 * Load flash message from session
 */
if(isset($_SESSION['message'])){
    $message = $_SESSION['message'];
    $messageType = $_SESSION['messageType'];
    unset($_SESSION['message']);
    unset($_SESSION['messageType']);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BORROW BOOKS</title>
</head>

    <style>
        body{
            display: flex;
            flex-direction: column;
            align-items: center;
            font-family: Arial, Helvetica, sans-serif;
        }

        .headers{
            background-color: #68ff7f;
            border-radius: 5px;
            padding: 10px;
        }

        .addbook_form{
            border-radius: 10px;
            padding: 30px;
            font-size: 1.2rem;
            width: 50%;
        }

        .bookInfo{
            margin-top: 10px;
        }

        .bookInfo input{
            background-color: hsla(0, 0%, 77%, 0.30);
            width: 100%;
            padding: 10px;
            box-sizing: border-box;
            border: none;
            border-radius: 5px;
            font-size: 1.2rem;
        }

        .bookInfo button{
            width: 100%;
            background-color: #68ff7f;
            width: 100%;
            padding: 10px;
            box-sizing: border-box;
            border: none;
            border-radius: 5px;
            font-size: 1.2rem;
            cursor: pointer;
        }

        .bookInfo button:hover{
            background-color: hsl(129, 100%, 50%);
        }

        .message {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
        }

        .message.success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .message.error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
    </style>

<body>
    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="POST" class="addbook_form">
        <h3 class="headers">Borrow Book</h3>
        <?php if($message): ?>
            <div class="message <?php echo $messageType; ?>">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>

        <div class="bookInfo">
            <label for="student_id">Student ID: </label>
            <input type="number" placeholder="studentID" name="student_id" id="student_id">
        </div>

        <div class="bookInfo">
            <label for="book_id">Book ID: </label>
            <input type="number" placeholder="BookID" name="book_id" id="book_id">
        </div>

        <div class="bookInfo">
            <label for="borrow_days">Days to borrow: </label>
            <input type="number" placeholder="Boorow Days" name="borrow_days" id="borrow_days" min='1' value=1>
        </div>

        <div class="bookInfo">
            <button type="submit" name="borrowBook">Borrow Book</button>
        </div>

    </form>
</body>
</html>