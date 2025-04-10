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
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['last_login'] = $user['last_login'];

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