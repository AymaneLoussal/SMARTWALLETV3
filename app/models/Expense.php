<?php

/**
 * Expense Model
 *
 * Handles all expense-related database operations
 * Manages CRUD operations, filtering, and aggregations for user expenses
 *
 * @package App\Models
 */
class Expense extends \core\Model {

    protected $table = 'expenses';

    /**
     * Constructor - Initialize Expense model
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * Create new expense (Domain-specific method)
     *
     * @param int $userId - User ID
     * @param float $amount - Expense amount
     * @param int|null $categoryId - Category ID (optional)
     * @param string|null $description - Description (optional)
     * @param string $date - Expense date
     * @return bool
     * @throws Exception
     */
    public function create($userId, $amount, $categoryId, $description, $date) {
        if ($amount <= 0) {
            throw new \Exception("Amount must be a positive number");
        }

        if (empty($date)) {
            throw new \Exception("Date is required");
        }

        try {
            $sql = "INSERT INTO expenses 
                    (user_id, category, amount, description, date) 
                    VALUES (?, ?, ?, ?, ?)";

            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                $userId,
                $categoryId,
                $amount,
                $description,
                $date
            ]);
        } catch (\PDOException $e) {
            throw new \Exception("Failed to create expense: " . $e->getMessage());
        }
    }

    /**
     * Get all expenses for user
     *
     * @param int $userId - User ID
     * @return array
     * @throws Exception
     */
    public function getAll($userId) {
        try {
            $sql = "SELECT * FROM expenses 
                    WHERE user_id = ? 
                    ORDER BY date DESC, id DESC";

            $stmt = $this->db->prepare($sql);
            $stmt->execute([$userId]);
            return $stmt->fetchAll(\PDO::FETCH_ASSOC) ?: [];
        } catch (\PDOException $e) {
            throw new \Exception("Failed to fetch expenses: " . $e->getMessage());
        }
    }

    /**
     * Get single expense by ID
     *
     * @param int $id - Expense ID
     * @param int $userId - User ID (for ownership check)
     * @return array|null
     * @throws Exception
     */
    public function getById($id, $userId) {
        try {
            $sql = "SELECT * FROM expenses 
                    WHERE id = ? AND user_id = ?";

            $stmt = $this->db->prepare($sql);
            $stmt->execute([$id, $userId]);
            return $stmt->fetch(\PDO::FETCH_ASSOC) ?: null;
        } catch (\PDOException $e) {
            throw new \Exception("Failed to fetch expense: " . $e->getMessage());
        }
    }

    /**
     * Get expenses by category
     *
     * @param int $categoryId - Category ID
     * @param int $userId - User ID
     * @return array
     * @throws Exception
     */
    public function getByCategory($categoryId, $userId) {
        try {
            $sql = "SELECT * FROM expenses 
                    WHERE category = ? AND user_id = ? 
                    ORDER BY date DESC";

            $stmt = $this->db->prepare($sql);
            $stmt->execute([$categoryId, $userId]);
            return $stmt->fetchAll(\PDO::FETCH_ASSOC) ?: [];
        } catch (\PDOException $e) {
            throw new \Exception("Failed to filter expenses: " . $e->getMessage());
        }
    }

    /**
     * Get total expenses for user
     *
     * @param int $userId - User ID
     * @return float
     * @throws Exception
     */
    public function getTotal($userId) {
        try {
            $sql = "SELECT COALESCE(SUM(amount), 0) as total 
                    FROM expenses 
                    WHERE user_id = ?";

            $stmt = $this->db->prepare($sql);
            $stmt->execute([$userId]);
            $result = $stmt->fetch(\PDO::FETCH_ASSOC);
            return (float)($result['total'] ?? 0);
        } catch (\PDOException $e) {
            throw new \Exception("Failed to calculate total: " . $e->getMessage());
        }
    }

    /**
     * Get expenses by date range
     *
     * @param int $userId - User ID
     * @param string $startDate - Start date (YYYY-MM-DD)
     * @param string $endDate - End date (YYYY-MM-DD)
     * @return array
     * @throws Exception
     */
    public function getByDateRange($userId, $startDate, $endDate) {
        try {
            $sql = "SELECT * FROM expenses 
                    WHERE user_id = ? AND date BETWEEN ? AND ? 
                    ORDER BY date DESC";

            $stmt = $this->db->prepare($sql);
            $stmt->execute([$userId, $startDate, $endDate]);
            return $stmt->fetchAll(\PDO::FETCH_ASSOC) ?: [];
        } catch (\PDOException $e) {
            throw new \Exception("Failed to filter by date range: " . $e->getMessage());
        }
    }

    /**
     * Update expense
     *
     * @param int $id - Expense ID
     * @param int $userId - User ID (for ownership check)
     * @param float $amount - New amount
     * @param int|null $categoryId - New category ID
     * @param string|null $description - New description
     * @param string $date - New date
     * @return bool
     * @throws Exception
     */
    public function update($id, $userId, $amount, $categoryId, $description, $date) {
        if ($amount <= 0) {
            throw new \Exception("Amount must be a positive number");
        }

        try {
            $sql = "UPDATE expenses 
                    SET category = ?, amount = ?, description = ?, date = ? 
                    WHERE id = ? AND user_id = ?";

            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                $categoryId,
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

    /**
     * Delete expense
     *
     * @param int $id - Expense ID
     * @param int $userId - User ID (for ownership check)
     * @return bool
     * @throws Exception
     */
    public function delete($id, $userId) {
        try {
            $sql = "DELETE FROM expenses WHERE id = ? AND user_id = ?";

            $stmt = $this->db->prepare($sql);
            return $stmt->execute([$id, $userId]);
        } catch (\PDOException $e) {
            throw new \Exception("Failed to delete expense: " . $e->getMessage());
        }
    }
}