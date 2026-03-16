<?php

namespace App\Models;

use App\Core\Database;
use mysqli;
use mysqli_result;

class User
{
    private mysqli $connection;

    public function __construct()
    {
        $database = new Database();
        $this->connection = $database->getConnection();
    }

    public function usernameExists(string $username, int $excludeId = 0): bool
    {
        if ($excludeId > 0) {
            $statement = $this->connection->prepare(
                "SELECT id FROM users WHERE username = ? AND id <> ?"
            );
            $statement->bind_param("si", $username, $excludeId);
        } else {
            $statement = $this->connection->prepare(
                "SELECT id FROM users WHERE username = ?"
            );
            $statement->bind_param("s", $username);
        }

        $statement->execute();
        $statement->store_result();

        return $statement->num_rows > 0;
    }

    public function getById(int $id): ?array
    {
        $statement = $this->connection->prepare(
            "SELECT id, username, account_type FROM users WHERE id = ?"
        );
        $statement->bind_param("i", $id);
        $statement->execute();

        $result = $statement->get_result();
        $user = $result->fetch_assoc();

        return $user ?: null;
    }

    public function getByUsername(string $username): ?array
    {
        $statement = $this->connection->prepare(
            "SELECT id, username, password, account_type FROM users WHERE username = ?"
        );
        $statement->bind_param("s", $username);
        $statement->execute();

        $result = $statement->get_result();
        $user = $result->fetch_assoc();

        return $user ?: null;
    }

    public function create(
        string $username,
        string $passwordHash,
        string $accountType,
        int $createdBy
    ): bool {
        $statement = $this->connection->prepare(
            "INSERT INTO users (username, password, account_type, created_on, created_by)
             VALUES (?, ?, ?, NOW(), ?)"
        );
        $statement->bind_param("sssi", $username, $passwordHash, $accountType, $createdBy);

        return $statement->execute();
    }

    public function update(
        int $id,
        string $username,
        string $accountType,
        int $updatedBy
    ): bool {
        $statement = $this->connection->prepare(
            "UPDATE users
             SET username = ?, account_type = ?, updated_on = NOW(), updated_by = ?
             WHERE id = ?"
        );
        $statement->bind_param("ssii", $username, $accountType, $updatedBy, $id);

        return $statement->execute();
    }

    public function updatePassword(int $id, string $passwordHash, int $updatedBy): bool
    {
        $statement = $this->connection->prepare(
            "UPDATE users
             SET password = ?, updated_on = NOW(), updated_by = ?
             WHERE id = ?"
        );
        $statement->bind_param("sii", $passwordHash, $updatedBy, $id);

        return $statement->execute();
    }

    public function getAll(): mysqli_result
    {
        $statement = $this->connection->prepare(
            "SELECT id, username, account_type, created_on, updated_on
             FROM users
             ORDER BY username ASC"
        );
        $statement->execute();

        return $statement->get_result();
    }
}