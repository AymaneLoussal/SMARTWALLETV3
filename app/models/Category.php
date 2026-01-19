<?php

/**
 * Category Model
 *
 * Manages category options and validation
 * Uses predefined categories for Phase 4
 * Future: Extend with user-defined categories via categories table (Phase 6)
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
     * Get all predefined income categories
     *
     * @return array
     */
    public static function getIncomeCategories() {
        return self::$incomeCategories;
    }
    
    /**
     * Get all predefined expense categories
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
    
    /**
     * Get income category options as HTML select element
     *
     * @return string - HTML option elements
     */
    public static function getIncomeOptions() {
        $options = '<option value="">Select Category</option>';
        foreach (self::$incomeCategories as $value) {
            $options .= '<option value="' . htmlspecialchars($value, ENT_QUOTES, 'UTF-8') . '">'
                     . htmlspecialchars($value, ENT_QUOTES, 'UTF-8') . '</option>';
        }
        return $options;
    }
    
    /**
     * Get expense category options as HTML select element
     *
     * @return string - HTML option elements
     */
    public static function getExpenseOptions() {
        $options = '<option value="">Select Category</option>';
        foreach (self::$expenseCategories as $value) {
            $options .= '<option value="' . htmlspecialchars($value, ENT_QUOTES, 'UTF-8') . '">'
                     . htmlspecialchars($value, ENT_QUOTES, 'UTF-8') . '</option>';
        }
        return $options;
    }

    /**
     * FUTURE: Get user-defined categories from database (Phase 6+)
     * This will be implemented when categories table is fully utilized
     *
     * @param int $userId - User ID
     * @return array
     */
    public function getUserCategories($userId) {
        // Phase 6: Implement custom user categories from database
        // For now, return empty array (use predefined categories instead)
        return [];
    }

    /**
     * FUTURE: Create custom category (Phase 6+)
     *
     * @param int $userId - User ID
     * @param string $name - Category name
     * @return bool
     */
    public function createCustom($userId, $name) {
        // Phase 6: Implement custom category creation
        // For now, not supported in Phase 4
        throw new \Exception("Custom categories coming in Phase 6");
    }
}

