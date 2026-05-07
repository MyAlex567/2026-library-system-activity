<?php
declare(strict_types=1);
session_start();
require_once __DIR__ . '../../vendor/autoload.php';

use App\Config\DatabaseConfig;
use App\Repository\BookRepository;
use App\Entity\Book;
use App\Exception\ValidationException;

$database = new DatabaseConfig();
$bookrepo = new BookRepository($database);

$message = '';
$messageType = '';

if(isset($_POST['BookList']) && $_SERVER['REQUEST_METHOD'] === 'POST'){
    header('Location: ' . '../src/View/Book_list.php');
    exit();
}

if(isset($_POST['borrow_book']) && $_SERVER['REQUEST_METHOD'] === 'POST'){
    header('Location: ' . '../src/View/Borrow_form.php');
    exit();
}

if(isset($_POST['addbook']) && $_SERVER['REQUEST_METHOD'] === 'POST'){
    try{
        $bookData = [
            'title' => $_POST['book_title'],
            'author' => $_POST['book_author'],
            'genre' => $_POST['book_genre'],
            'year' => (int)$_POST['book_year'],
        ];

        $book = new Book(
        $bookData['title'],
        $bookData['author'],
        $bookData['year'],
        $bookData['genre']  
        );

        $result = $bookrepo->addBook($book);


        if(isset($result)){
            $_SESSION['message'] = 'Add book Success';
            $_SESSION['messageType'] = 'success';
        }else{
            $_SESSION['message'] = 'Failed to add a book';
            $_SESSION['messageType'] = 'error';     
        }
    }catch(ValidationException){
        $_SESSION['message'] = 'Failed to add a book';
        $_SESSION['messageType'] = 'error';   
    }

    $book = null;

    header('Location: ' . $_SERVER['PHP_SELF']);
    exit();
}

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
    <title>index php</title>


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

        .form-container{
            width: 100%;
            padding: 10px;
            box-sizing: border-box;
            display: flex;

        }

        .other-function-container{
            display: flex;
            flex-direction: column;
            align-items: center;
            width: 50%;
            padding: 20px;
            box-sizing: border-box;
        }

        .function-container{
            width: 100%;
        }

    </style>

</head>
<body>

    <h1>2026-libray-system</h1>

    <div class="form-container">
        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="POST" class="addbook_form">
            <h3 class="headers">Add Book</h3>
            <?php if($message): ?>
                <div class="message <?php echo $messageType; ?>">
                    <?php echo $message; ?>
                </div>
            <?php endif; ?>

            <div class="bookInfo">
                <label for="book_title">Title: </label>
                <input type="text" placeholder="enter the title" name="book_title" id="book_title">
            </div>

            <div class="bookInfo">
                <label for="book_author">Author: </label>
                <input type="text" placeholder="enter the author" name="book_author" id="book_author">
            </div>

            <div class="bookInfo">
                <label for="book_genre">Genre: </label>
                <input type="text" placeholder="enter the genre" name="book_genre" id="book_genre">
            </div>


            <div class="bookInfo">
                <label for="book_year">Year: </label>
                <input type="int" placeholder="enter the Year" name="book_year" id="book_year">
            </div>

            <div class="bookInfo">
                <button type="submit" name="addbook">Add Book</button>
            </div>

        </form>

        <div class="other-function-container">
            <h3 class="headers">OTHER FUNCTIONS</h3>
            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="POST" class="function-container">
                <div class="bookInfo">
                    <button type="submit" name="BookList">View Book</button>
                </div>

                <div class="bookInfo">
                    <button type="submit" name="borrow_book">Borrow Book</button>
                </div>

            </form>
        </div>

    </div>
    
</body>
</html>