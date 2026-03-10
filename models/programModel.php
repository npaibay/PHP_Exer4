<?php
    class ProgramModel 
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
                $stmt = $this->conn->prepare("SELECT program_id FROM program WHERE code=? AND program_id<>?");
                $stmt->bind_param("si", $code, $exclude_id);
            } 
            else 
            {
                $stmt = $this->conn->prepare("SELECT program_id FROM program WHERE code=?");
                $stmt->bind_param("s", $code);
            }
            $stmt->execute();
            $stmt->store_result();
            return $stmt->num_rows > 0;
        }

        public function create(string $code, string $title, int $years, int $created_by): bool 
        {
            $stmt = $this->conn->prepare("
                INSERT INTO program (code, title, years, created_on, created_by)
                VALUES (?, ?, ?, NOW(), ?)
            ");
            $stmt->bind_param("ssii", $code, $title, $years, $created_by);
            return $stmt->execute();
        }

        public function getById(int $id): ?array 
        {
            $stmt = $this->conn->prepare("SELECT * FROM program WHERE program_id=?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $row = $stmt->get_result()->fetch_assoc();
            return $row ?: null;
        }

        public function update(int $id, string $code, string $title, int $years, int $updated_by): bool 
        {
            $stmt = $this->conn->prepare("
                UPDATE program
                SET code=?, title=?, years=?, updated_on=NOW(), updated_by=?
                WHERE program_id=?
            ");
            $stmt->bind_param("ssiii", $code, $title, $years, $updated_by, $id);
            return $stmt->execute();
        }

        public function search(string $search_text = ""): mysqli_result 
        {
            if ($search_text !== "") 
            {
                $like = "%".$search_text."%";
                $stmt = $this->conn->prepare("SELECT * FROM program WHERE code LIKE ? ORDER BY code ASC");
                $stmt->bind_param("s", $like);
            } 
            else 
            {
                $stmt = $this->conn->prepare("SELECT * FROM program ORDER BY code ASC");
            }
            $stmt->execute();
            return $stmt->get_result();
        }

        public function delete()
        {
            
        }
    }
?>