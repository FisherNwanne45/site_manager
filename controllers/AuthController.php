<?php
class AuthController
{
    private $userModel;

    public function __construct($pdo)
    {
        $this->userModel = new User($pdo);
    }

    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = trim($_POST['username']);
            $password = $_POST['password'];

            $user = $this->userModel->login($username, $password);

            if ($user) {
                // Set all session variables including role
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['user_role'] = $user['role']; // CRUCIAL ADDITION
                $_SESSION['last_login'] = $user['last_login'];
                $_SESSION['is_active'] = $user['is_active'];

                // Regenerate session ID to prevent fixation
                session_regenerate_id(true);

                header('Location: index.php?action=dashboard');
                exit;
            } else {
                $error = "Nome utente o password non validi";
                require APP_PATH . '/views/auth/login.php';
                exit;
            }
        }

        require APP_PATH . '/views/auth/login.php';
    }

    public function logout()
    {
        // Unset all session variables
        $_SESSION = array();

        // Destroy the session
        session_destroy();

        // Redirect to login page
        header('Location: index.php?action=login');
        exit;
    }

    public function checkPermission($requiredRole)
    {
        if (empty($_SESSION['user_id'])) {
            header('Location: index.php?action=login');
            exit();
        }

        // Verify session matches database
        $user = $this->userModel->getUserById($_SESSION['user_id']);
        if (!$user || !$user['is_active'] || $user['username'] !== $_SESSION['username']) {
            $this->logout();
        }

        $roleHierarchy = [
            'viewer' => 1,
            'manager' => 2,
            'super_admin' => 3
        ];

        if (!isset($roleHierarchy[$user['role']])) {
            $this->logout();
        }

        if ($roleHierarchy[$user['role']] < $roleHierarchy[$requiredRole]) {
            $_SESSION['error'] = "Accesso negato: autorizzazioni insufficienti";
            header('Location: index.php?action=dashboard');
            exit();
        }
    }

    // User Management Methods (Super Admin only)
    public function showCreateForm()
    {
        $this->checkPermission('super_admin');
        require APP_PATH . '/views/users/create.php';
    }

    public function createUser()
    {
        $this->checkPermission('super_admin');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?action=users&do=create');
            exit;
        }

        $data = [
            'username' => trim($_POST['username']),
            'password' => $_POST['password'],
            'email' => trim($_POST['email']), // Add this line
            'role' => $_POST['role']
        ];

        try {
            if ($this->userModel->createUser($data)) {
                $_SESSION['message'] = "Utente creato con successo";
                header('Location: index.php?action=users&do=list');
            }
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            $_SESSION['form_data'] = $_POST; // Preserve form input
            header('Location: index.php?action=users&do=create');
        }
        exit;
    }
    public function listUsers()
    {
        $this->checkPermission('super_admin');
        $users = $this->userModel->getAllUsers();
        require APP_PATH . '/views/users/list.php';
    }

    public function showEditForm($userId)
    {
        $this->checkPermission('super_admin');
        $user = $this->userModel->getUserById($userId);
        if (!$user) {
            $_SESSION['error'] = "Utente non trovato";
            header('Location: index.php?action=users&do=list');
            exit;
        }
        require APP_PATH . '/views/users/edit.php';
    }

    public function updateUser($userId)
    {
        $this->checkPermission('super_admin');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: index.php?action=users&do=edit&id=$userId");
            exit;
        }

        $data = [
            'id' => $userId,
            'username' => trim($_POST['username']),
            'role' => $_POST['role'],
            'email' => $_POST['email'],
            'is_active' => isset($_POST['is_active']) ? 1 : 0
        ];

        if ($this->userModel->updateUser($data)) {
            $_SESSION['message'] = "Utente aggiornato con successo";
        } else {
            $_SESSION['error'] = "Impossibile aggiornare l'utente";
        }
        header("Location: index.php?action=users&do=edit&id=$userId");
        exit;
    }

    public function changePassword()
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?action=login');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $currentPassword = $_POST['current_password'];
            $newPassword = $_POST['new_password'];
            $confirmPassword = $_POST['confirm_password'];

            if ($newPassword !== $confirmPassword) {
                $error = "Le nuove password non corrispondono";
            } elseif (strlen($newPassword) < 8) {
                $error = "La password deve essere lunga almeno 8 caratteri";
            } else {
                $success = $this->userModel->changePassword(
                    $_SESSION['user_id'],
                    $currentPassword,
                    $newPassword
                );

                if ($success) {
                    $message = "Password modificata con successo";
                } else {
                    $error = "La password corrente è errata";
                }
            }
        }

        require APP_PATH . '/views/settings/password.php';
    }
}
