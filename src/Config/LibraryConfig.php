<?php
declare(strict_types=1);
namespace App\Config;

/**
 * Library configuration constants
 * 
 * This class contains all fixed system-wide setiings used across
 * the library management system
 * - Borrowing rules
 * - Daily fine computation rates
 * - Status identifiers
 * 
 * @author lisayAlex
 * @since 2026-05-08
 */

class LibraryConfig{

    /**
     * @var string status when a book is returned
     */
    public const STATUS_RETURNED = 'returned';

    /**
     * @var string status when a book is currently borrowed
     */
    public const STATUS_BORROWED = 'borrowed';

    /**
     * @var int Default number of days a book can be borrowed
     */
    public const DEFAULT_BORROW_DAYS = 14;

    /**
     * @var float Fine rate per day for overdue books (in PHP currency)
     */
    public const DAILY_FINE_RATE = 5.00;

    /**
     * @var int Maximum number of books a student can borrow at once
     */
    public const MAX_BORROW_LIMIT = 3;

    /**
     * @var int Number of seconds in one day (used for time calculations)
     */
    public const SECONDS_PER_DAY = 60 * 60 * 24;
}

?>