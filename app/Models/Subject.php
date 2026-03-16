<?php

namespace App\Models;

use App\Core\Database;
use mysqli;
use mysqli_result;

class Subject
{
    private mysqli $connection;

    public function __construct()
    {
        $database = new Database();
        $this->connection = $database->getConnection();
    }

    public function codeExists(string $code, int $excludeId = 0): bool
    {
        if ($excludeId > 0) {
            $statement = $this->connection->prepare(
                "SELECT subject_id FROM subject WHERE code = ? AND subject_id <> ?"
            );
            $statement->bind_param("si", $code, $excludeId);
        } else {
            $statement = $this->connection->prepare(
                "SELECT subject_id FROM subject WHERE code = ?"
            );
            $statement->bind_param("s", $code);
        }

        $statement->execute();
        $statement->store_result();

        return $statement->num_rows > 0;
    }

    public function create(string $code, string $title, int $unit, int $createdBy): bool
    {
        $statement = $this->connection->prepare(
            "INSERT INTO subject (code, title, unit, created_on, created_by)
             VALUES (?, ?, ?, NOW(), ?)"
        );
        $statement->bind_param("ssii", $code, $title, $unit, $createdBy);

        return $statement->execute();
    }

    public function getById(int $id): ?array
    {
        $statement = $this->connection->prepare(
            "SELECT * FROM subject WHERE subject_id = ?"
        );
        $statement->bind_param("i", $id);
        $statement->execute();

        $result = $statement->get_result();
        $subject = $result->fetch_assoc();

        return $subject ?: null;
    }

    public function update(
        int $id,
        string $code,
        string $title,
        int $unit,
        int $updatedBy
    ): bool {
        $statement = $this->connection->prepare(
            "UPDATE subject
             SET code = ?, title = ?, unit = ?, updated_on = NOW(), updated_by = ?
             WHERE subject_id = ?"
        );
        $statement->bind_param("ssiii", $code, $title, $unit, $updatedBy, $id);

        return $statement->execute();
    }

    public function getAll(): mysqli_result
    {
        $statement = $this->connection->prepare(
            "SELECT * FROM subject ORDER BY code ASC"
        );
        $statement->execute();

        return $statement->get_result();
    }

    public function search(string $searchText = ''): mysqli_result
    {
        if ($searchText !== '') {
            $like = '%' . $searchText . '%';

            $statement = $this->connection->prepare(
                "SELECT * FROM subject
                 WHERE code LIKE ? OR title LIKE ?
                 ORDER BY code ASC"
            );
            $statement->bind_param("ss", $like, $like);
        } else {
            $statement = $this->connection->prepare(
                "SELECT * FROM subject ORDER BY code ASC"
            );
        }

        $statement->execute();

        return $statement->get_result();
    }

    public function delete(int $id): bool
    {
        $statement = $this->connection->prepare(
            "DELETE FROM subject WHERE subject_id = ?"
        );
        $statement->bind_param("i", $id);

        return $statement->execute();
    }
}