<?php
declare(strict_types=1);

namespace App\Config;

use PDO;
use PDOException;
use RuntimeException;
use App\Exception\DatabaseException;

class DatabaseConfig{
    private PDO $connection;

    private const HOSTNAME = 'localhost';
    private const USERNAME = 'root';
    private const PASSWORD = '';
    private const DBNAME = 'library_db';

    public function __construct(){
        $this->connect();
    }

    private function connect(): void 
    {
        try{
            $dsn = "mysql:host=" . self::HOSTNAME . ";dbname=" . self::DBNAME . ";charset=utf8mb4";

            $this->connection = new PDO(
                $dsn,
                self::USERNAME,
                self::PASSWORD,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                ]
            );
         
            
        }catch(PDOException $error){
            throw new DatabaseException("Database connection failed: " . $error->getMessage());
        }
    }

    public function getConnection(): PDO
    {
        return $this->connection;
    }
}

?>