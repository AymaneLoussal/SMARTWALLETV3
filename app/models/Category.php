<?php

/**
 * Category Model
 *
 * Handles category data and management
 * Provides both static utilities for categories and CRUD database operations
 */
class Category extends \core\Model {

    protected $table = 'categories';

    // Static category options for initial setup
    private static $incomeCategories = [
        'Salary' => 'Salary',
        'Bonus' => 'Bonus', 
        'Investment' => 'Investment',
        'Freelance' => 'Freelance',
        'Gift' => 'Gift',
        'Other' => 'Other',
    ];
    
    private static $expenseCategories = [
        'Food' => 'Food',
        'Rent' => 'Rent',
        'Transport' => 'Transport',
        'Shopping' => 'Shopping',
        'Entertainment' => 'Entertainment',
        'Bills' => 'Bills',
        'Healthcare' => 'Healthcare',
        'Other' => 'Other',
    ];

    /**
     * Get all income categories (static)
     *
     * @return array
     */
    public static function getIncomeCategories() {
        return self::$incomeCategories;
    }
    
    /**
     * Get all expense categories (static)
     *
     * @return array
     */
    public static function getExpenseCategories() {
        return self::$expenseCategories;
    }
    
    /**
     * Check if category is valid (static)
     *
     * @param string $type - 'income' or 'expense'
     * @param string $category - Category name
     * @return bool
     */
    public static function isValidCategory($type, $category) {
        if ($type == 'income') {
            return in_array($category, self::$incomeCategories);
        } elseif ($type == 'expense') {
            return in_array($category, self::$expenseCategories);
        }
        return false;
    }
    
    /**
     * Get income category options as HTML (static)
     *
     * @return string - HTML options
     */
    public static function getIncomeOptions() {
        $options = '<option value="">Select Category</option>';
        foreach (self::$incomeCategories as $key => $value) {
            $options .= '<option value="' . htmlspecialchars($value) . '">' . htmlspecialchars($value) . '</option>';
        }
        return $options;
    }
    
    /**
     * Get expense category options as HTML (static)
     *
     * @return string - HTML options
     */
    public static function getExpenseOptions() {
        $options = '<option value="">Select Category</option>';
        foreach (self::$expenseCategories as $key => $value) {
            $options .= '<option value="' . htmlspecialchars($value) . '">' . htmlspecialchars($value) . '</option>';
        }
        return $options;
    }

    /**
     * Get all categories for a user
     *
     * @param int $userId - User ID
     * @return array
     */
    public function getAllByUser($userId) {
        try {
            $sql = "SELECT * FROM categories WHERE user_id = ? ORDER BY name ASC";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$userId]);
            return $stmt->fetchAll(\PDO::FETCH_ASSOC) ?: [];
        } catch (\PDOException $e) {
            throw new \Exception("Query error: " . $e->getMessage());
        }
    }

    /**
     * Get category by ID for a user
     *
     * @param int $id - Category ID
     * @param int $userId - User ID (for security)
     * @return array|null
     */
    public function getById($id, $userId) {
        try {
            $sql = "SELECT * FROM categories WHERE id = ? AND user_id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$id, $userId]);
            return $stmt->fetch(\PDO::FETCH_ASSOC) ?: null;
        } catch (\PDOException $e) {
            throw new \Exception("Query error: " . $e->getMessage());
        }
    }

    /**
     * Create new category for user
     *
     * @param int $userId - User ID
     * @param string $name - Category name
     * @return bool
     */
    public function create($userId, $name) {
        try {
            $sql = "INSERT INTO categories (user_id, name) VALUES (?, ?)";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([$userId, $name]);
        } catch (\PDOException $e) {
            throw new \Exception("Create error: " . $e->getMessage());
        }
    }

    /**
     * Delete category for user
     *
     * @param int $id - Category ID
     * @param int $userId - User ID (for security)
     * @return bool
     */
    public function delete($id, $userId) {
        try {
            $sql = "DELETE FROM categories WHERE id = ? AND user_id = ?";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([$id, $userId]);
        } catch (\PDOException $e) {
            throw new \Exception("Delete error: " . $e->getMessage());
        }
    }
}

