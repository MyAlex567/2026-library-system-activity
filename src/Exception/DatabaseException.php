<?php
declare(strict_types=1);

namespace App\Exception;

use Override;
use RuntimeException;
use Throwable;


/**
 * DatabaseException
 * 
 * Custom exception class used for handling database-related errors
 * within the library system. This ensures that all database failures
 * are handled consistently and separately from general application errors.
 *
 * Responsibilities:
 * - Wrap PDO/database errors
 * - Provide meaningful error context for debugging
 * - Standardize database error handling across the system
 * 
 * @author lisayAlex
 * @since 2026-05-08
 */
class DatabaseException extends RuntimeException{

    /**
     * DatabaseException constructor
     *
     * Initializes a custom database exception with optional message,
     * error code, and previous throwable for chaining exceptions.
     *
     * @param string $message Error message description
     * @param int $code Error code (optional)
     * @param Throwable or null $previous Previous exception for chaining
     */
    #[Override]
    public function __construct(string $message = "", int $code = 0, Throwable|null $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}

?>