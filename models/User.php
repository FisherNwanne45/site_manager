<?php
class User
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function login($username, $password)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password_hash'])) {
            $this->updateLastLogin($user['id']);
            return $user;
        }
        return false;
    }

    private function updateLastLogin($userId)
    {
        $stmt = $this->pdo->prepare("UPDATE users SET last_login = NOW() WHERE id = ?");
        $stmt->execute([$userId]);
    }

    public function changePassword($userId, $currentPassword, $newPassword)
    {
        $stmt = $this->pdo->prepare("SELECT password_hash FROM users WHERE id = ?");
        $stmt->execute([$userId]);
        $user = $stmt->fetch();

        if ($user && password_verify($currentPassword, $user['password_hash'])) {
            $newHash = password_hash($newPassword, PASSWORD_DEFAULT);
            $stmt = $this->pdo->prepare("UPDATE users SET password_hash = ? WHERE id = ?");
            return $stmt->execute([$newHash, $userId]);
        }
        return false;
    }

    public function getUserById($userId)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$userId]);
        return $stmt->fetch();
    }

    public function createUser($data)
    {
        // First check if username exists
        $check = $this->pdo->prepare("SELECT id FROM users WHERE username = ?");
        $check->execute([$data['username']]);

        if ($check->fetch()) {
            throw new Exception("Il nome utente esiste già");
        }

        $stmt = $this->pdo->prepare("INSERT INTO users (username, password_hash, email, role, is_active) VALUES (?, ?, ?, ?, 1)");
        $hash = password_hash($data['password'], PASSWORD_DEFAULT);
        return $stmt->execute([
            $data['username'],
            $hash,
            $data['email'], // Now required
            $data['role']
        ]);
    }

    public function updateUser($data)
    {
        $stmt = $this->pdo->prepare("UPDATE users SET username = ?, role = ?, email = ?, is_active = ? WHERE id = ?");
        return $stmt->execute([
            $data['username'],
            $data['role'],
            $data['email'],
            $data['is_active'],
            $data['id']
        ]);
    }

    public function getAllUsers()
    {
        $stmt = $this->pdo->prepare("SELECT id, username, email, role, is_active FROM users WHERE id != ? ORDER BY username");
        $stmt->execute([$_SESSION['user_id']]);
        return $stmt->fetchAll();
    }

    public function hasPermission($userId, $requiredRole)
    {
        $user = $this->getUserById($userId);
        if (!$user || !$user['is_active']) return false;

        $roleHierarchy = [
            'viewer' => 1,
            'manager' => 2,
            'super_admin' => 3
        ];

        return isset($roleHierarchy[$user['role']]) &&
            $roleHierarchy[$user['role']] >= $roleHierarchy[$requiredRole];
    }
}
