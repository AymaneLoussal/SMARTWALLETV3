<?php

namespace core;


class Model
{
    protected $db;
    protected $table;

    public function __construct()
    {
        try {
            $this->db = \core\Database::getInstance()->getConnection();
        } catch (\Exception $e) {
            throw new \Exception("Failed to initialize database: " . $e->getMessage());
        }
    }

   
    public function all()
    {
        try {
            $stmt = $this->db->query("SELECT * FROM {$this->table}");
            return $stmt->fetchAll(\PDO::FETCH_ASSOC) ?: [];
        } catch (\PDOException $e) {
            throw new \Exception("Query error: " . $e->getMessage());
        }
    }

    
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
