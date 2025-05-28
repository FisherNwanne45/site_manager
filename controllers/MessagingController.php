<?php
class MessagingController
{
    private $threadModel;
    private $groupModel;
    private $userModel;
    private $emailModel;

    public function __construct($threadModel, $groupModel, $userModel, $emailModel)
    {
        $this->threadModel = $threadModel;
        $this->groupModel = $groupModel;
        $this->userModel = $userModel;
        $this->emailModel = $emailModel;
    }

    public function inbox()
    {
        try {
            if (!isset($_SESSION['user_id'])) {
                throw new Exception("User not logged in");
            }

            $threads = $this->threadModel->getUserThreads($_SESSION['user_id']);

            if (empty($threads)) {
                error_log("No threads found for user: " . $_SESSION['user_id']);
                $threads = []; // Ensure it's at least an empty array
            }

            return [
                'view' => 'messaging/inbox',
                'data' => [
                    'threads' => $threads,
                    'debug' => ['user_id' => $_SESSION['user_id'], 'thread_count' => count($threads)]
                ]
            ];
        } catch (Exception $e) {
            error_log("MessagingController Error: " . $e->getMessage());
            return [
                'view' => 'error',
                'data' => ['message' => 'Could not load messages. Error: ' . $e->getMessage()]
            ];
        }
    }

    public function viewThread($threadId)
    {
        $messages = $this->threadModel->getThreadMessages($threadId, $_SESSION['user_id']);
        return [
            'view' => 'messaging/thread',
            'data' => [
                'messages' => $messages,
                'threadId' => $threadId
            ]
        ];
    }

    public function compose()
    {
        $groups = $this->groupModel->getUserGroups($_SESSION['user_id']);
        $users = $this->userModel->getAllUsers();
        return [
            'view' => 'messaging/compose',
            'data' => [
                'groups' => $groups,
                'users' => $users
            ]
        ];
    }

    public function send()
    {
        $isGroupThread = !empty($_POST['group_id']);
        $subject = $_POST['subject'] ?? 'No subject';

        if ($isGroupThread) {
            $threadId = $this->threadModel->createThread(
                $subject,
                $_SESSION['user_id'],
                [],
                $_POST['content'],
                $_POST['group_id']
            );
        } else {
            $threadId = $this->threadModel->createThread(
                $subject,
                $_SESSION['user_id'],
                $_POST['recipients'],
                $_POST['content']
            );
        }

        // Send email only for first message
        if ($threadId) {
            $this->sendEmailNotifications($threadId);
        }

        header("Location: ?action=messaging&do=view&id=$threadId");
        exit;
    }

    public function listGroups()
    {
        $groups = $this->groupModel->getUserGroups($_SESSION['user_id']);
        return [
            'view' => 'messaging/groups/list',
            'data' => ['groups' => $groups]
        ];
    }

    private function sendEmailNotifications($threadId)
    {
        $firstMessage = $this->threadModel->getFirstMessage($threadId);
        $recipients = $this->threadModel->getThreadParticipants($threadId, $_SESSION['user_id']);

        foreach ($recipients as $recipient) {
            try {
                $subject = "New message: {$firstMessage['subject']}";
                $content = "
                    <h1>New Message Notification</h1>
                    <p>You have received a new message from {$_SESSION['username']}:</p>
                    <div class='card'>
                        " . nl2br(htmlspecialchars($firstMessage['content'])) . "
                    </div>
                    <p>View the full conversation: <a href='" . BASE_PATH . "?action=messaging&do=view&id=$threadId'>Click here</a></p>
                ";

                // Use your existing email template system
                $emailBody = $this->emailModel->getEmailTemplate($subject, $content);

                // Prepare PHPMailer using your existing configuration
                $smtpSettings = $this->emailModel->getSmtpSettings();
                if (!$smtpSettings) {
                    error_log("SMTP settings not configured for messaging");
                    continue;
                }

                require_once APP_PATH . '/vendor/autoload.php';
                $mail = new PHPMailer\PHPMailer\PHPMailer(true);
                $mail = $this->emailModel->configureMailer($mail, $smtpSettings);

                $mail->setFrom($smtpSettings['from_email'], $smtpSettings['from_name']);
                $mail->addAddress($recipient['email']);
                $mail->Subject = $subject;
                $mail->Body = $emailBody;
                $mail->AltBody = strip_tags($content);

                $success = $mail->send();

                // Log the email using your existing system
                $this->emailModel->logEmail([
                    'email_type' => 'message_notification',
                    'sent_to' => $recipient['email'],
                    'subject' => $subject,
                    'body' => $content,
                    'status' => $success ? 'sent' : 'failed',
                    'error_message' => $success ? null : $mail->ErrorInfo
                ]);
            } catch (Exception $e) {
                error_log("Failed to send message notification: " . $e->getMessage());
                $this->emailModel->logEmail([
                    'email_type' => 'message_notification',
                    'sent_to' => $recipient['email'],
                    'subject' => $subject ?? 'New Message Notification',
                    'body' => $content ?? '',
                    'status' => 'failed',
                    'error_message' => $e->getMessage()
                ]);
            }
        }
    }
}