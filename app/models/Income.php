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

    protected $table = 'incomes';

    /**
     * Constructor - Initialize Income model
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * Create new income entry (Domain-specific method)
     *
     * @param int $userId - User ID
     * @param float $amount - Income amount
     * @param string|null $category - Category name (optional)
     * @param string|null $description - Description
     * @param string $date - Income date (YYYY-MM-DD)
     *
     * @return bool
     * @throws Exception
     */
    public function create($userId, $amount, $category, $description, $date) {
        // Validation
        if ($amount <= 0) {
            throw new \Exception("Amount must be a positive number");
        }

        if (empty($date)) {
            throw new \Exception("Date is required");
        }

        try {
            $sql = "INSERT INTO incomes (user_id, category, amount, description, date) 
                    VALUES (?, ?, ?, ?, ?)";
            $stmt = $this->db->prepare($sql);

            return $stmt->execute([
                $userId,
                $category,
                $amount,
                $description,
                $date
            ]);

        } catch (\PDOException $e) {
            throw new \Exception("Database error: " . $e->getMessage());
        }
    }

    /**
     * Get all incomes for a user
     *
     * @param int $userId - User ID
     * @return array
     * @throws Exception
     */
    public function getAll($userId) {
        try {
            $sql = "SELECT * FROM incomes WHERE user_id = ? ORDER BY date DESC, id DESC";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$userId]);
            return $stmt->fetchAll(\PDO::FETCH_ASSOC) ?: [];
        } catch (\PDOException $e) {
            throw new \Exception("Database error: " . $e->getMessage());
        }
    }

    /**
     * Get income by ID
     *
     * @param int $id - Income ID
     * @param int $userId - User ID (for security)
     * @return array|null
     * @throws Exception
     */
    public function getById($id, $userId) {
        try {
            $sql = "SELECT * FROM incomes WHERE id = ? AND user_id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$id, $userId]);
            return $stmt->fetch(\PDO::FETCH_ASSOC) ?: null;
        } catch (\PDOException $e) {
            throw new \Exception("Database error: " . $e->getMessage());
        }
    }

    /**
     * Get incomes by category
     *
     * @param string $category - Category name
     * @param int $userId - User ID
     * @return array
     * @throws Exception
     */
    public function getByCategory($category, $userId) {
        try {
            $sql = "SELECT * FROM incomes WHERE category = ? AND user_id = ? ORDER BY date DESC";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$category, $userId]);
            return $stmt->fetchAll(\PDO::FETCH_ASSOC) ?: [];
        } catch (\PDOException $e) {
            throw new \Exception("Database error: " . $e->getMessage());
        }
    }

    /**
     * Get total income for user
     *
     * @param int $userId - User ID
     * @return float
     * @throws Exception
     */
    public function getTotal($userId) {
        try {
            $sql = "SELECT COALESCE(SUM(amount), 0) as total FROM incomes WHERE user_id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$userId]);
            $result = $stmt->fetch(\PDO::FETCH_ASSOC);
            return (float)($result['total'] ?? 0);
        } catch (\PDOException $e) {
            throw new \Exception("Database error: " . $e->getMessage());
        }
    }

    /**
     * Get incomes by date range
     *
     * @param int $userId - User ID
     * @param string $startDate - Start date (YYYY-MM-DD)
     * @param string $endDate - End date (YYYY-MM-DD)
     * @return array
     * @throws Exception
     */
    public function getByDateRange($userId, $startDate, $endDate) {
        try {
            $sql = "SELECT * FROM incomes 
                    WHERE user_id = ? AND date BETWEEN ? AND ? 
                    ORDER BY date DESC";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$userId, $startDate, $endDate]);
            return $stmt->fetchAll(\PDO::FETCH_ASSOC) ?: [];
        } catch (\PDOException $e) {
            throw new \Exception("Database error: " . $e->getMessage());
        }
    }

    /**
     * Update income
     *
     * @param int $id - Income ID
     * @param int $userId - User ID (for security)
     * @param float $amount - New amount
     * @param string|null $category - New category name
     * @param string|null $description - New description
     * @param string $date - New date
     * @return bool
     * @throws Exception
     */
    public function update($id, $userId, $amount, $category, $description, $date) {
        if ($amount <= 0) {
            throw new \Exception("Amount must be a positive number");
        }

        try {
            $sql = "UPDATE incomes 
                    SET category = ?, amount = ?, description = ?, date = ? 
                    WHERE id = ? AND user_id = ?";

            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                $category,
                $amount,
                $description,
                $date,
                $id,
                $userId
            ]);
        } catch (\PDOException $e) {
            throw new \Exception("Database error: " . $e->getMessage());
        }
    }

    /**
     * Get total by category
     *
     * @param int $userId - User ID
     * @param string $category - Category name
     * @return float
     * @throws Exception
     */
    public function getTotalByCategory($userId, $category): float {
        try {
            $sql = "SELECT SUM(amount) as total FROM incomes 
                    WHERE user_id = ? AND category = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$userId, $category]);
            $result = $stmt->fetch(\PDO::FETCH_ASSOC);
            return (float)($result['total'] ?? 0);
        } catch (\PDOException $e) {
            throw new \Exception("Database error: " . $e->getMessage());
        }
    }

    /**
     * Delete income
     *
     * @param int $id - Income ID
     * @param int $userId - User ID (for security)
     * @return bool
     * @throws Exception
     */
    public function delete($id, $userId) {
        try {
            $sql = "DELETE FROM incomes WHERE id = ? AND user_id = ?";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([$id, $userId]);
        } catch (\PDOException $e) {
            throw new \Exception("Database error: " . $e->getMessage());
        }
    }

}

