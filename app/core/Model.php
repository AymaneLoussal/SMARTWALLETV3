<?php

namespace core;

/**
 * Model Base Class
 *
 * Provides common database functionality for all models
 */
class Model
{
    protected $db;
    protected $table;

    /**
     * Constructor - Initialize database connection
     */
    public function __construct()
    {
        try {
            $this->db = \core\Database::getInstance()->getConnection();
        } catch (\Exception $e) {
            throw new \Exception("Failed to initialize database: " . $e->getMessage());
        }
    }

    /**
     * Get all records from table
     *
     * @return array - All records
     */
    public function all()
    {
        try {
            $stmt = $this->db->query("SELECT * FROM {$this->table}");
            return $stmt->fetchAll(\PDO::FETCH_ASSOC) ?: [];
        } catch (\PDOException $e) {
            throw new \Exception("Query error: " . $e->getMessage());
        }
    }

    /**
     * Find record by ID
     *
     * @param int $id - Record ID
     * @return array|null - Record data or null
     */
    public function find($id)
    {
        try {
            $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE id = ?");
            $stmt->execute([$id]);
            return $stmt->fetch(\PDO::FETCH_ASSOC) ?: null;
        } catch (\PDOException $e) {
            throw new \Exception("Query error: " . $e->getMessage());
        }
    }


    /**
     * Execute custom query
     *
     * @param string $sql - SQL query
     * @param array $params - Query parameters
     * @return \PDOStatement
     */
    protected function query($sql, $params = [])
    {
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            return $stmt;
        } catch (\PDOException $e) {
            throw new \Exception("Query error: " . $e->getMessage());
        }
    }
}
