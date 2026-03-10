<?php
    class Subject
    {
        private mysqli $conn;

        public function __construct(mysqli $conn) 
        {
            $this->conn = $conn;
        }

        public function codeExists(string $code, int $exclude_id = 0): bool 
        {
            if ($exclude_id > 0) 
            {
                $stmt = $this->conn->prepare("SELECT subject_id FROM subject WHERE code=? AND subject_id<>?");
                $stmt->bind_param("si", $code, $exclude_id);
            } 
            else 
            {
                $stmt = $this->conn->prepare("SELECT subject_id FROM subject WHERE code=?");
                $stmt->bind_param("s", $code);
            }
            $stmt->execute();
            $stmt->store_result();
            return $stmt->num_rows > 0;
        }

        public function create(string $code, string $title, int $unit, int $created_by): bool 
        {
            $stmt = $this->conn->prepare("
                INSERT INTO subject (code, title, unit, created_on, created_by)
                VALUES (?, ?, ?, NOW(), ?)
            ");
            $stmt->bind_param("ssii", $code, $title, $unit, $created_by);
            return $stmt->execute();
        }

        public function getById(int $id): ?array 
        {
            $stmt = $this->conn->prepare("SELECT * FROM subject WHERE subject_id=?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $row = $stmt->get_result()->fetch_assoc();
            return $row ?: null;
        }

        public function update(int $id, string $code, string $title, int $unit, int $updated_by): bool 
        {
            $stmt = $this->conn->prepare("
                UPDATE subject
                SET code=?, title=?, unit=?, updated_on=NOW(), updated_by=?
                WHERE subject_id=?
            ");
            $stmt->bind_param("ssiii", $code, $title, $unit, $updated_by, $id);
            return $stmt->execute();
        }

        public function search(string $search_text = ""): mysqli_result 
        {
            if ($search_text !== "") 
            {
                $like = "%".$search_text."%";
                $stmt = $this->conn->prepare("SELECT * FROM subject WHERE code LIKE ? ORDER BY code ASC");
                $stmt->bind_param("s", $like);
            } 
            else 
            {
                $stmt = $this->conn->prepare("SELECT * FROM subject ORDER BY code ASC");
            }
            $stmt->execute();
            return $stmt->get_result();
        }

        public function delete()
        {
            
        }
    }
?>