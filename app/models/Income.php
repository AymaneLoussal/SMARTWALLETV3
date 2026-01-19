<?php

/**
 * Income Model
 *
 * Handles all income-related database operations
 * Manages CRUD operations, filtering, and aggregations for user incomes
 *
 * @package App\Models
 */
class Income extends \core\Model {

    // Properties
    private ?int $id = null;
    private int $user_id;
    private ?int $category_id = null;
    private float $amount;
    private ?string $description = null;
    private ?string $date = null;
    private ?string $created_at = null;
    private ?string $updated_at = null;

    
    public function __construct() {
        parent::__construct();
    }

    
    public function createIncome(int $userId, float $amount, ?int $categoryId, ?string $description, string $date): bool {
        // Validation
        if ($amount <= 0) {
            throw new Exception("Amount must be a positive number");
        }

        if (empty($date)) {
            throw new Exception("Date is required");
        }

        try {
            // Get category name from ID
            $categoryName = null;
            if ($categoryId) {
                $catSql = "SELECT name FROM categories WHERE id = ?";
                $catStmt = $this->db->prepare($catSql);
                $catStmt->execute([$categoryId]);
                $catResult = $catStmt->fetch(PDO::FETCH_ASSOC);
                $categoryName = $catResult ? $catResult['name'] : null;
            }

            $sql = "INSERT INTO incomes (user_id, category, amount, description, date) 
                    VALUES (?, ?, ?, ?, ?)";
            $stmt = $this->db->prepare($sql);

            return $stmt->execute([
                $userId,
                $categoryName,
                $amount,
                $description,
                $date
            ]);

        } catch (PDOException $e) {
            throw new Exception("Database error: " . $e->getMessage());
        }
    }

   
    public function getAll(int $userId): array {
        try {
            $sql = "SELECT * FROM incomes WHERE user_id = ? ORDER BY date DESC, id DESC";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$userId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
        } catch (PDOException $e) {
            throw new Exception("Database error: " . $e->getMessage());
        }
    }

    
    public function getById(int $id, int $userId): ?array {
        try {
            $sql = "SELECT * FROM incomes WHERE id = ? AND user_id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$id, $userId]);
            return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
        } catch (PDOException $e) {
            throw new Exception("Database error: " . $e->getMessage());
        }
    }

    
    public function getByCategory(int $userId, int $categoryId): array {
        try {
            $sql = "SELECT * FROM incomes WHERE user_id = ? AND category_id = ? 
                    ORDER BY date DESC, id DESC";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$userId, $categoryId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
        } catch (PDOException $e) {
            throw new Exception("Database error: " . $e->getMessage());
        }
    }

    
    public function getByDateRange(int $userId, string $startDate, string $endDate): array {
        try {
            $sql = "SELECT * FROM incomes 
                    WHERE user_id = ? AND date BETWEEN ? AND ? 
                    ORDER BY date DESC";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$userId, $startDate, $endDate]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
        } catch (PDOException $e) {
            throw new Exception("Database error: " . $e->getMessage());
        }
    }

    
    public function updateIncome(int $id, int $userId, float $amount, ?int $categoryId, ?string $description, string $date): bool {
        // Validation
        if ($amount <= 0) {
            throw new Exception("Amount must be a positive number");
        }

        try {
            // Get category name from ID
            $categoryName = null;
            if ($categoryId) {
                $catSql = "SELECT name FROM categories WHERE id = ?";
                $catStmt = $this->db->prepare($catSql);
                $catStmt->execute([$categoryId]);
                $catResult = $catStmt->fetch(PDO::FETCH_ASSOC);
                $categoryName = $catResult ? $catResult['name'] : null;
            }

            $sql = "UPDATE incomes 
                    SET amount = ?, category = ?, description = ?, date = ?, updated_at = CURRENT_TIMESTAMP
                    WHERE id = ? AND user_id = ?";

            $stmt = $this->db->prepare($sql);

            return $stmt->execute([
                $amount,
                $categoryName,
                $description,
                $date,
                $id,
                $userId
            ]);

        } catch (PDOException $e) {
            throw new Exception("Database error: " . $e->getMessage());
        }
    }

   
    public function deleteIncome(int $id, int $userId): bool {
        try {
            $sql = "DELETE FROM incomes WHERE id = ? AND user_id = ?";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([$id, $userId]);
        } catch (PDOException $e) {
            throw new Exception("Database error: " . $e->getMessage());
        }
    }

    
    public function getTotal(int $userId): float {
        try {
            $sql = "SELECT SUM(amount) as total FROM incomes WHERE user_id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$userId]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return (float)($result['total'] ?? 0);
        } catch (PDOException $e) {
            throw new Exception("Database error: " . $e->getMessage());
        }
    }

    
    public function getTotalByCategory(int $userId, int $categoryId): float {
        try {
            $sql = "SELECT SUM(amount) as total FROM incomes 
                    WHERE user_id = ? AND category_id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$userId, $categoryId]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return (float)($result['total'] ?? 0);
        } catch (PDOException $e) {
            throw new Exception("Database error: " . $e->getMessage());
        }
    }

    
    public function getRecent(int $userId, int $limit = 10): array {
        try {
            $sql = "SELECT * FROM incomes WHERE user_id = ? 
                    ORDER BY date DESC, id DESC LIMIT ?";
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(1, $userId, PDO::PARAM_INT);
            $stmt->bindValue(2, $limit, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
        } catch (PDOException $e) {
            throw new Exception("Database error: " . $e->getMessage());
        }
    }

    
    public function getMonthlyTotal(int $userId, int $year, int $month): float {
        try {
            $sql = "SELECT SUM(amount) as total FROM incomes 
                    WHERE user_id = ? 
                    AND YEAR(date) = ? AND MONTH(date) = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$userId, $year, $month]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return (float)($result['total'] ?? 0);
        } catch (PDOException $e) {
            throw new Exception("Database error: " . $e->getMessage());
        }
    }

    
    private function validateCategory(int $categoryId, int $userId): bool {
        try {
            $sql = "SELECT id FROM categories WHERE id = ? AND user_id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$categoryId, $userId]);
            return $stmt->fetch(PDO::FETCH_ASSOC) !== false;
        } catch (PDOException $e) {
            throw new Exception("Database error: " . $e->getMessage());
        }
    }
}