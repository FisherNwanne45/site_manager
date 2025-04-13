<?php
class SettingsController
{
    private $emailModel;
    private $cronModel;

    public function __construct($pdo)
    {
        $this->emailModel = new Email($pdo);
        $this->cronModel = new CronModel($pdo);
    }

    public function smtp()
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?action=login');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'host' => $_POST['host'],
                'port' => $_POST['port'],
                'username' => $_POST['username'],
                'password' => $_POST['password'],
                'encryption' => $_POST['encryption'],
                'from_email' => $_POST['from_email'],
                'from_name' => $_POST['from_name'],
                'cc_email' => $_POST['cc_email'] ?? null // Add this line
            ];

            $success = $this->emailModel->updateSmtpSettings($data);

            if ($success) {
                $_SESSION['message'] = "Impostazioni SMTP aggiornate correttamente";
            } else {
                $error = "Errore durante l'aggiornamento delle impostazioni SMTP";
            }
        }

        $smtpSettings = $this->emailModel->getSmtpSettings();
        require APP_PATH . '/views/settings/smtp.php';
    }

    public function testSmtp()
    {
        // Set JSON header first to ensure no HTML output
        header('Content-Type: application/json');

        try {
            if (!isset($_SESSION['user_id'])) {
                throw new Exception('Non autorizzato');
            }

            $smtpSettings = $this->emailModel->getSmtpSettings();
            if (!$smtpSettings) {
                throw new Exception('Impostazioni SMTP non configurate');
            }

            // Get test email from POST data
            $testEmail = $_POST['test_email'] ?? $smtpSettings['from_email'];

            // Validate email format
            if (!filter_var($testEmail, FILTER_VALIDATE_EMAIL)) {
                throw new Exception('Indirizzo email di prova non valido');
            }

            require_once APP_PATH . '/vendor/autoload.php';

            $mail = new PHPMailer\PHPMailer\PHPMailer(true);

            // Server settings
            $mail->isSMTP();
            $mail->Host = $smtpSettings['host'];
            $mail->SMTPAuth = true;
            $mail->Username = $smtpSettings['username'];
            $mail->Password = $smtpSettings['password'];
            $mail->Port = $smtpSettings['port'];

            // Handle encryption
            if ($smtpSettings['encryption'] === 'starttls') {
                $mail->SMTPSecure = false;
                $mail->SMTPAutoTLS = true;
            } else {
                $mail->SMTPSecure = $smtpSettings['encryption'];
            }

            // Recipients
            $mail->setFrom($smtpSettings['from_email'], $smtpSettings['from_name']);
            $mail->addAddress($testEmail);

            // Add CC email if configured
            if (!empty($smtpSettings['cc_email'])) {
                $mail->addCC($smtpSettings['cc_email']);
            }

            // Content
            $mail->isHTML(true);
            $mail->Subject = 'E-mail di prova SMTP';
            $mail->Body = '<h1>Test SMTP riuscito</h1><p>Le tue impostazioni SMTP funzionano correttamente.</p>';
            $mail->AltBody = 'Test SMTP riuscito: le impostazioni SMTP funzionano correttamente.';

            if (!$mail->send()) {
                throw new Exception($mail->ErrorInfo);
            }

            // Log the test email
            $logData = [
                'email_type' => 'manual',
                'sent_to' => $testEmail,
                'subject' => $mail->Subject,
                'body' => $mail->Body,
                'status' => 'sent'
            ];

            // Include CC in the log message if it exists
            $message = 'Test email sent to ' . $testEmail;
            if (!empty($smtpSettings['cc_email'])) {
                $message .= ' and CC to ' . $smtpSettings['cc_email'];
                $logData['cc'] = $smtpSettings['cc_email'];
            }

            $this->emailModel->logEmail($logData);

            echo json_encode(['success' => true, 'message' => $message]);
        } catch (Exception $e) {
            // Log the failed attempt
            if (isset($testEmail)) {
                $logData = [
                    'email_type' => 'manual',
                    'sent_to' => $testEmail,
                    'subject' => 'E-mail di prova SMTP',
                    'body' => '',
                    'status' => 'failed',
                    'error_message' => $e->getMessage()
                ];

                if (!empty($smtpSettings['cc_email'])) {
                    $logData['cc'] = $smtpSettings['cc_email'];
                }

                $this->emailModel->logEmail($logData);
            }

            http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
        exit;
    }

    public function advanced()
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?action=login');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $isActive = isset($_POST['cron_active']) && $_POST['cron_active'] === '1';
            $success = $this->cronModel->updateCronStatus($isActive);

            if ($success) {
                $_SESSION['message'] = "Impostazioni cron aggiornate correttamente";
            } else {
                $_SESSION['error'] = "Errore durante l'aggiornamento delle impostazioni cron";
            }
        }

        $cronStatus = $this->cronModel->getCronStatus();
        $lastRun = $this->cronModel->getLastRunTime();

        require APP_PATH . '/views/settings/advanced.php';
    }
}
