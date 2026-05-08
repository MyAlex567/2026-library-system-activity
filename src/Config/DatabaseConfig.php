<?php
declare(strict_types=1);

namespace App\Config;

use PDO;
use PDOException;
use RuntimeException;
use App\Exception\DatabaseException;

/**
 * 
 * Database configuration and connection handler
 * 
 * This connection PDO connection to MySQL with predefined constants
 * for configuration
 * 
 * 
 * @author lisayAlex
 * @since 2026-05-08
 * 
 */

class DatabaseConfig
{
    /**
     * @var PDO Active PDO database connection instance
     */
    private PDO $connection;

    /**
     * @var string Database host
     */
    private const HOSTNAME = 'localhost';

    /**
     * @var string Database username
     */
    private const USERNAME = 'root';

    /**
     * @var string Database password
     */
    private const PASSWORD = '';

    /**
     * @var string Database name
     */
    private const DBNAME = 'library_db';

    /**
     * Initializes database connection automatically.
     */
    public function __construct()
    {
        $this->connect();
    }

    /**
     * Established PDO connection to the database
     * 
     * Uses Mysql DSN with UTF-8 encoding and secure PDO options:
     * - ERRMODE_EXCEPTION for error handling
     * - FETCH_ASSOC for associative arrays
     * - disables emulated prepared statements
     * 
     * @return void
     * 
     * @throws DatabaseException if connection fails
     */
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

    /**
     * Returns activve database connection instance
     * 
     * @return PDO The active PDO connection
     */
    public function getConnection(): PDO
    {
        return $this->connection;
    }
}

?>