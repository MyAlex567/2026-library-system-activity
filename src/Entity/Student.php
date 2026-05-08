<?php
declare(strict_types=1);

namespace App\Entity;

/**
 * Student Entity
 *
 * Represents a student in the library system.
 * Stores basic student information used for borrowing books
 * and tracking library transactions.
 *
 * Responsibilities:
 * - Store student identity information
 * - Provide access to student data via getters
 *
 * @author lisayAlex
 * @since 2026-05-08
 */
class Student{

    /**
     * @var int or null Unique identifier of the student
     */
    private ?int $StudentId;

    /**
     * @var string Full name of the student
     */
    private string $name;

    /**
     * Student constructor
     *
     * Initializes a Student entity with optional ID and required name.
     *
     * @param int or null $studentId Student ID (null if not yet saved)
     * @param string $name Full name of the student
     */
    public function __construct(
        ?int $StudentId,
        string $name
    ){
        $this->StudentId = $StudentId;
        $this->name = $name;
    }

    /**
     * Get student ID
     *
     * @return int or null Student identifier
     */
    public function getStudentId(): ?int
    {
        return $this->StudentId;
    }

    /**
     * Get student name
     *
     * @return string Student full name
     */
    public function getName(): string
    {
        return $this->name;
    }
}

?>