<?php


class Expense extends \core\Model {

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
        $this->table = 'expenses';
    }

    
    public function createExpense($userId, $amount, $categoryId, $description, $date) {
        try {
            // Get category name from ID
            $categoryName = null;
            if ($categoryId) {
                $catSql = "SELECT name FROM categories WHERE id = ?";
                $catStmt = $this->db->prepare($catSql);
                $catStmt->execute([$categoryId]);
                $catResult = $catStmt->fetch(\PDO::FETCH_ASSOC);
                
                if (!$catResult) {
                    throw new \Exception("Category with ID {$categoryId} not found");
                }
                
                $categoryName = $catResult['name'];
            }

            // Category is required (NOT NULL in database)
            if (!$categoryName) {
                throw new \Exception("Category is required");
            }

            $sql = "INSERT INTO {$this->table} 
                    (user_id, category, amount, description, date) 
                    VALUES (?, ?, ?, ?, ?)";

            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                $userId,
                $categoryName,
                $amount,
                $description,
                $date
            ]);
        } catch (\PDOException $e) {
            throw new \Exception("Failed to create expense: " . $e->getMessage());
        }
    }

    
    public function getAll($userId) {
        try {
            $sql = "SELECT * FROM {$this->table} 
                    WHERE user_id = ? 
                    ORDER BY date DESC, id DESC";

            $stmt = $this->db->prepare($sql);
            $stmt->execute([$userId]);
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            throw new \Exception("Failed to fetch expenses: " . $e->getMessage());
        }
    }

    
    public function getById($id, $userId) {
        try {
            $sql = "SELECT * FROM {$this->table} 
                    WHERE id = ? AND user_id = ?";

            $stmt = $this->db->prepare($sql);
            $stmt->execute([$id, $userId]);
            return $stmt->fetch(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            throw new \Exception("Failed to fetch expense: " . $e->getMessage());
        }
    }

    
    public function getByCategory($categoryId, $userId) {
        try {
            $sql = "SELECT * FROM {$this->table} 
                    WHERE category_id = ? AND user_id = ? 
                    ORDER BY date DESC";

            $stmt = $this->db->prepare($sql);
            $stmt->execute([$categoryId, $userId]);
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            throw new \Exception("Failed to filter expenses: " . $e->getMessage());
        }
    }

    
    public function getTotal($userId) {
        try {
            $sql = "SELECT COALESCE(SUM(amount), 0) as total 
                    FROM {$this->table} 
                    WHERE user_id = ?";

            $stmt = $this->db->prepare($sql);
            $stmt->execute([$userId]);
            $result = $stmt->fetch(\PDO::FETCH_ASSOC);
            return (float)($result['total'] ?? 0);
        } catch (\PDOException $e) {
            throw new \Exception("Failed to calculate total: " . $e->getMessage());
        }
    }

    
    public function updateExpense($id, $userId, $amount, $categoryId, $description, $date) {
        try {
            // Get category name from ID
            $categoryName = null;
            if ($categoryId) {
                $catSql = "SELECT name FROM categories WHERE id = ?";
                $catStmt = $this->db->prepare($catSql);
                $catStmt->execute([$categoryId]);
                $catResult = $catStmt->fetch(\PDO::FETCH_ASSOC);
                $categoryName = $catResult ? $catResult['name'] : null;
            }

            $sql = "UPDATE {$this->table} 
                    SET category = ?, amount = ?, description = ?, date = ? 
                    WHERE id = ? AND user_id = ?";

            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                $categoryName,
                $amount,
                $description,
                $date,
                $id,
                $userId
            ]);
        } catch (\PDOException $e) {
            throw new \Exception("Failed to update expense: " . $e->getMessage());
        }
    }

    
    public function deleteExpense($id, $userId) {
        try {
            $sql = "DELETE FROM {$this->table} 
                    WHERE id = ? AND user_id = ?";

            $stmt = $this->db->prepare($sql);
            return $stmt->execute([$id, $userId]);
        } catch (\PDOException $e) {
            throw new \Exception("Failed to delete expense: " . $e->getMessage());
        }
    }

    
    public function getByDateRange($userId, $startDate, $endDate) {
        try {
            $sql = "SELECT * FROM {$this->table} 
                    WHERE user_id = ? AND date BETWEEN ? AND ? 
                    ORDER BY date DESC";

            $stmt = $this->db->prepare($sql);
            $stmt->execute([$userId, $startDate, $endDate]);
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            throw new \Exception("Failed to filter by date range: " . $e->getMessage());
        }
    }

    // Getters
    public function getId() { return $this->id; }
    public function getUserId() { return $this->user_id; }
    public function getCategoryId() { return $this->category_id; }
    public function getAmount() { return $this->amount; }
    public function getDescription() { return $this->description; }
    public function getDate() { return $this->date; }
    public function getCreatedAt() { return $this->created_at; }
    public function getUpdatedAt() { return $this->updated_at; }

    // Setters
    public function setUserId($userId) { $this->user_id = $userId; }
    public function setCategoryId($categoryId) { $this->category_id = $categoryId; }
    public function setAmount($amount) { $this->amount = $amount; }
    public function setDescription($description) { $this->description = $description; }
    public function setDate($date) { $this->date = $date; }
}