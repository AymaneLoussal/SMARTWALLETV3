<?php

/**
 * Category Model
 *
 * Manages category options and validation
 * Handles both predefined and user-defined categories
 */
class Category extends \core\Model {

    protected $table = 'categories';

    // Predefined income categories
    private static $incomeCategories = [
        'Salary' => 'Salary',
        'Bonus' => 'Bonus', 
        'Investment' => 'Investment',
        'Freelance' => 'Freelance',
        'Gift' => 'Gift',
        'Other' => 'Other',
    ];
    
    // Predefined expense categories
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
     * Get all categories for a user (from database)
     *
     * @param int $userId - User ID
     * @return array - Categories from database
     * @throws Exception
     */
    public function getAllByUser($userId) {
        try {
            // Get categories from database for the system user (user_id = 1)
            // Categories are shared among all users in Phase 4
            $sql = "SELECT id, name FROM {$this->table} WHERE user_id = 1 ORDER BY id";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();

            $categories = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            // If no categories in database, return empty array
            return $categories ?: [];

        } catch (\PDOException $e) {
            // If there's a database error, fall back to predefined categories
            $allCategories = array_merge(
                self::$incomeCategories,
                self::$expenseCategories
            );

            // Convert to expected format: array of arrays with id and name
            $result = [];
            $id = 1;
            foreach ($allCategories as $name) {
                $result[] = [
                    'id' => $id++,
                    'name' => $name
                ];
            }
            return $result;
        }
    }

    /**
     * Get category by ID
     *
     * @param int $id - Category ID
     * @param int $userId - User ID (for ownership check)
     * @return array|null
     * @throws Exception
     */
    public function getById($id, $userId) {
        try {
            $sql = "SELECT * FROM {$this->table} WHERE id = ? AND user_id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$id, $userId]);
            return $stmt->fetch(\PDO::FETCH_ASSOC) ?: null;
        } catch (\PDOException $e) {
            throw new \Exception("Failed to fetch category: " . $e->getMessage());
        }
    }

    /**
     * Create a new category
     *
     * @param int $userId - User ID
     * @param string $name - Category name
     * @param string $type - Category type ('income' or 'expense')
     * @return bool
     * @throws Exception
     */
    public function createCategory($userId, $name, $type = 'expense') {
        try {
            // Validate type
            if (!in_array($type, ['income', 'expense'])) {
                throw new \Exception("Invalid category type. Must be 'income' or 'expense'");
            }

            // Check if category already exists for this user
            $checkSql = "SELECT id FROM {$this->table} WHERE user_id = ? AND name = ?";
            $checkStmt = $this->db->prepare($checkSql);
            $checkStmt->execute([$userId, $name]);
            
            if ($checkStmt->fetch()) {
                throw new \Exception("Category already exists");
            }

            $sql = "INSERT INTO {$this->table} (user_id, name, type) VALUES (?, ?, ?)";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([$userId, $name, $type]);
        } catch (\PDOException $e) {
            throw new \Exception("Failed to create category: " . $e->getMessage());
        }
    }

    /**
     * Update category
     *
     * @param int $id - Category ID
     * @param int $userId - User ID (for ownership check)
     * @param string $name - New category name
     * @param string $type - Category type
     * @return bool
     * @throws Exception
     */
    public function updateCategory($id, $userId, $name, $type) {
        try {
            $sql = "UPDATE {$this->table} SET name = ?, type = ? WHERE id = ? AND user_id = ?";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([$name, $type, $id, $userId]);
        } catch (\PDOException $e) {
            throw new \Exception("Failed to update category: " . $e->getMessage());
        }
    }

    /**
     * Delete category
     *
     * @param int $id - Category ID
     * @param int $userId - User ID (for ownership check)
     * @return bool
     * @throws Exception
     */
    public function deleteCategory($id, $userId) {
        try {
            $sql = "DELETE FROM {$this->table} WHERE id = ? AND user_id = ?";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([$id, $userId]);
        } catch (\PDOException $e) {
            throw new \Exception("Failed to delete category: " . $e->getMessage());
        }
    }

    /**
     * Get categories by type
     *
     * @param int $userId - User ID
     * @param string $type - Category type ('income' or 'expense')
     * @return array
     * @throws Exception
     */
    public function getByType($userId, $type) {
        try {
            $sql = "SELECT id, name FROM {$this->table} WHERE user_id = 1 AND type = ? ORDER BY name ASC";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$type]);
            return $stmt->fetchAll(\PDO::FETCH_ASSOC) ?: [];
        } catch (\PDOException $e) {
            throw new \Exception("Failed to fetch categories: " . $e->getMessage());
        }
    }

    /**
     * Initialize default categories for a new user
     *
     * @param int $userId - User ID
     * @return bool
     * @throws Exception
     */
    public function initializeDefaultCategories($userId) {
        try {
            // Check if user already has categories
            $checkSql = "SELECT COUNT(*) as count FROM {$this->table} WHERE user_id = ?";
            $checkStmt = $this->db->prepare($checkSql);
            $checkStmt->execute([$userId]);
            $result = $checkStmt->fetch(\PDO::FETCH_ASSOC);
            
            if ($result['count'] > 0) {
                return true; // Already initialized
            }

            // Insert default income categories
            foreach (self::$incomeCategories as $category) {
                $this->createCategory($userId, $category, 'income');
            }

            // Insert default expense categories
            foreach (self::$expenseCategories as $category) {
                $this->createCategory($userId, $category, 'expense');
            }

            return true;
        } catch (\Exception $e) {
            throw new \Exception("Failed to initialize categories: " . $e->getMessage());
        }
    }

    /**
     * Get all predefined income categories (static)
     *
     * @return array
     */
    public static function getIncomeCategories() {
        return self::$incomeCategories;
    }
    
    /**
     * Get all predefined expense categories (static)
     *
     * @return array
     */
    public static function getExpenseCategories() {
        return self::$expenseCategories;
    }
    
    /**
     * Check if category is valid
     *
     * @param string $type - 'income' or 'expense'
     * @param string $category - Category name to validate
     * @return bool
     */
    public static function isValidCategory($type, $category) {
        if ($type === 'income') {
            return isset(self::$incomeCategories[$category]);
        } elseif ($type === 'expense') {
            return isset(self::$expenseCategories[$category]);
        }
        return false;
    }
}