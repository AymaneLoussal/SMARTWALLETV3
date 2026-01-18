<?php

/**
 * User Model
 *
 * Handles user persistence and authentication
 */
class User extends \core\Model
{
    protected $table = 'users';

    private $id;
    private $full_name;
    private $email;
    private $password;
    private $created_at;

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Find user by email
     *
     * @param string $email
     * @return array|null
     */
    public function findByEmail(string $email): ?array
    {
        try {
            $stmt = $this->db->prepare("SELECT * FROM users WHERE email = ?");
            $stmt->execute([$email]);
            $user = $stmt->fetch(\PDO::FETCH_ASSOC);
            return $user ?: null;
        } catch (\PDOException $e) {
            throw new \Exception("Database error: " . $e->getMessage());
        }
    }

    /**
     * Register a new user
     *
     * @param string $full_name
     * @param string $email
     * @param string $password
     * @return bool
     */
    public function register(string $full_name, string $email, string $password): bool
    {
        // Basic validation
        if (strlen($full_name) < 3) {
            throw new \Exception('Full name is too short');
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new \Exception('Invalid email format');
        }
        if (strlen($password) < 6) {
            throw new \Exception('Password must be at least 6 characters');
        }

        if ($this->findByEmail($email)) {
            throw new \Exception('Email already exists');
        }

        $hashed = password_hash($password, PASSWORD_DEFAULT);

        try {
            $stmt = $this->db->prepare("INSERT INTO users (full_name, email, password) VALUES (?, ?, ?)");
            return $stmt->execute([$full_name, $email, $hashed]);
        } catch (\PDOException $e) {
            throw new \Exception("Database error: " . $e->getMessage());
        }
    }

    /**
     * Authenticate user
     *
     * @param string $email
     * @param string $password
     * @return bool
     */
    public function login(string $email, string $password): bool
    {
        $user = $this->findByEmail($email);
        if (!$user) {
            throw new \Exception('User not found');
        }

        if (!password_verify($password, $user['password'])) {
            throw new \Exception('Invalid credentials');
        }

        // Populate local properties
        $this->id = $user['id'];
        $this->full_name = $user['full_name'] ?? null;
        $this->email = $user['email'] ?? null;
        $this->created_at = $user['created_at'] ?? null;

        return true;
    }

    /**
     * Find user by ID
     *
     * @param int $id
     * @return array|null
     */
    public function findById(int $id): ?array
    {
        try {
            $stmt = $this->db->prepare("SELECT id, full_name, email, created_at FROM users WHERE id = ?");
            $stmt->execute([$id]);
            $user = $stmt->fetch(\PDO::FETCH_ASSOC);
            return $user ?: null;
        } catch (\PDOException $e) {
            throw new \Exception("Database error: " . $e->getMessage());
        }
    }

    /**
     * Check if email exists
     *
     * @param string $email
     * @return bool
     */
    public function emailExists(string $email): bool
    {
        return $this->findByEmail($email) !== null;
    }

    /**
     * Get user id after login
     *
     * @return int|null
     */
    public function getUserId(): ?int
    {
        return $this->id ?? null;
    }
}

