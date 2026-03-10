<?php
    class Users 
    {
        private mysqli $conn;

        public function __construct(mysqli $conn) 
        {
            $this->conn = $conn;
        }

        public function usernameExists(string $username, int $exclude_id = 0): bool 
        {
            if ($exclude_id > 0) 
            {
                $stmt = $this->conn->prepare("SELECT id FROM users WHERE username=? AND id<>?");
                $stmt->bind_param("si", $username, $exclude_id);
            } 
            else 
            {
                $stmt = $this->conn->prepare("SELECT id FROM users WHERE username=?");
                $stmt->bind_param("s", $username);
            }
            $stmt->execute();
            $stmt->store_result();
            return $stmt->num_rows > 0;
        }

        public function getById(int $id): ?array 
        {
            $stmt = $this->conn->prepare("SELECT id, username, account_type FROM users WHERE id=?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $row = $stmt->get_result()->fetch_assoc();
            return $row ?: null;
        }

        public function create(string $username, string $password_hash, string $account_type, int $created_by): bool 
        {
            $stmt = $this->conn->prepare("
                INSERT INTO users (username, password, account_type, created_on, created_by)
                VALUES (?, ?, ?, NOW(), ?)
            ");
            $stmt->bind_param("sssi", $username, $password_hash, $account_type, $created_by);
            return $stmt->execute();
        }

        public function update(int $id, string $username, string $account_type, int $updated_by): bool 
        {
            $stmt = $this->conn->prepare("
                UPDATE users
                SET username=?, account_type=?, updated_on=NOW(), updated_by=?
                WHERE id=?
            ");
            $stmt->bind_param("ssii", $username, $account_type, $updated_by, $id);
            return $stmt->execute();
        }

        public function getAll(): mysqli_result 
        {
            $stmt = $this->conn->prepare("
                SELECT id, username, account_type, created_on, updated_on
                FROM users
                ORDER BY username ASC
            ");
            $stmt->execute();
            return $stmt->get_result();
        }
    }
?>