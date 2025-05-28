<?php
class MessageThread
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function createThread($subject, $creatorId, $participantIds, $content, $groupId = null)
    {
        $this->db->beginTransaction();
        try {
            // Create thread
            $stmt = $this->db->prepare("
                INSERT INTO message_threads (subject, group_id) 
                VALUES (?, ?)
            ");
            $stmt->execute([$subject, $groupId]);
            $threadId = $this->db->lastInsertId();

            // Add participants
            $stmt = $this->db->prepare("
                INSERT INTO thread_participants (thread_id, user_id) 
                VALUES (?, ?)
            ");
            foreach (array_unique(array_merge([$creatorId], $participantIds)) as $userId) {
                $stmt->execute([$threadId, $userId]);
            }

            // Add first message
            $this->addMessage($threadId, $creatorId, $content, true);

            $this->db->commit();
            return $threadId;
        } catch (Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    public function addMessage($threadId, $senderId, $content, $isFirst = false)
    {
        $stmt = $this->db->prepare("
            INSERT INTO messages (thread_id, sender_id, content, is_first) 
            VALUES (?, ?, ?, ?)
        ");
        $stmt->execute([$threadId, $senderId, $content, $isFirst]);
        return $this->db->lastInsertId();
    }

    public function getThreadMessages($threadId, $userId)
    {
        // Mark as read
        $this->markAsRead($threadId, $userId);

        // Get messages
        $stmt = $this->db->prepare("
            SELECT m.*, u.username 
            FROM messages m
            JOIN users u ON m.sender_id = u.id
            WHERE m.thread_id = ?
            ORDER BY m.created_at ASC
        ");
        $stmt->execute([$threadId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function markAsRead($threadId, $userId)
    {
        $stmt = $this->db->prepare("
            UPDATE thread_participants 
            SET last_read_at = NOW() 
            WHERE thread_id = ? AND user_id = ?
        ");
        $stmt->execute([$threadId, $userId]);
    }

    public function getUnreadCount($userId)
    {
        $stmt = $this->db->prepare("
            SELECT COUNT(*) 
            FROM messages m
            JOIN thread_participants tp ON m.thread_id = tp.thread_id
            WHERE tp.user_id = ? 
            AND (tp.last_read_at IS NULL OR m.created_at > tp.last_read_at)
            AND m.sender_id != ?
        ");
        $stmt->execute([$userId, $userId]);
        return $stmt->fetchColumn();
    }

    // NEW METHODS NEEDED BY CONTROLLER
    public function getUserThreads($userId)
    {
        $stmt = $this->db->prepare("
            SELECT 
                t.id,
                t.subject,
                t.created_at,
                (SELECT content FROM messages WHERE thread_id = t.id ORDER BY created_at DESC LIMIT 1) as last_message,
                (SELECT COUNT(*) FROM messages m 
                 JOIN thread_participants tp ON m.thread_id = tp.thread_id 
                 WHERE tp.user_id = ? AND tp.thread_id = t.id 
                 AND (tp.last_read_at IS NULL OR m.created_at > tp.last_read_at)
                 AND m.sender_id != ?) as unread_count,
                (SELECT username FROM users u 
                 JOIN messages m ON m.sender_id = u.id 
                 WHERE m.thread_id = t.id 
                 ORDER BY m.created_at DESC LIMIT 1) as last_sender
            FROM message_threads t
            JOIN thread_participants p ON t.id = p.thread_id
            WHERE p.user_id = ?
            ORDER BY (SELECT MAX(created_at) FROM messages WHERE thread_id = t.id) DESC
        ");
        $stmt->execute([$userId, $userId, $userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getFirstMessage($threadId)
    {
        $stmt = $this->db->prepare("
            SELECT m.content, t.subject 
            FROM messages m
            JOIN message_threads t ON m.thread_id = t.id
            WHERE m.thread_id = ? AND m.is_first = TRUE
            LIMIT 1
        ");
        $stmt->execute([$threadId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getThreadParticipants($threadId, $excludeUserId = null)
    {
        $sql = "
            SELECT u.id, u.email, u.username 
            FROM thread_participants tp
            JOIN users u ON tp.user_id = u.id
            WHERE tp.thread_id = ?
        ";

        $params = [$threadId];

        if ($excludeUserId) {
            $sql .= " AND tp.user_id != ?";
            $params[] = $excludeUserId;
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}