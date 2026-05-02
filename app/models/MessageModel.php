<?php

class MessageModel {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function createMessage($data) {
        $ticket_number = 'TCK-' . strtoupper(substr(uniqid(), -8));
        $this->db->query("INSERT INTO messages (user_id, email, ticket_number, subject, message, status, is_read_admin, is_read_user) VALUES (:user_id, :email, :ticket_number, :subject, :message, 'active', 0, 1)");
        $this->db->bind(':user_id', $data['user_id'] ?? null);
        $this->db->bind(':email', $data['email'] ?? null);
        $this->db->bind(':ticket_number', $ticket_number);
        $this->db->bind(':subject', $data['subject']);
        $this->db->bind(':message', $data['message']);
        if ($this->db->execute()) {
            return $this->db->lastInsertId();
        }
        return false;
    }

    public function getAllMessages($includeArchived = false) {
        $sql = "SELECT m.*, COALESCE(u.name, 'Guest') as user_name, COALESCE(u.email, m.email) as user_email 
                FROM messages m 
                LEFT JOIN users u ON m.user_id = u.id";
        
        if (!$includeArchived) {
            $sql .= " WHERE m.is_archived_admin = 0";
        }
        
        $sql .= " ORDER BY m.updated_at DESC";
        
        $this->db->query($sql);
        return $this->db->resultSet();
    }

    public function getMessagesByUserId($user_id, $includeArchived = false) {
        $sql = "SELECT * FROM messages WHERE user_id = :user_id";
        
        if (!$includeArchived) {
            $sql .= " AND is_archived_user = 0";
        }
        
        $sql .= " ORDER BY updated_at DESC";
        
        $this->db->query($sql);
        $this->db->bind(':user_id', $user_id);
        return $this->db->resultSet();
    }

    public function addReply($message_id, $sender_id, $reply_text, $new_status) {
        $this->db->query("INSERT INTO message_replies (message_id, sender_id, reply_text) VALUES (:message_id, :sender_id, :reply_text)");
        $this->db->bind(':message_id', $message_id);
        $this->db->bind(':sender_id', $sender_id); // Can be NULL now
        $this->db->bind(':reply_text', $reply_text);

        if ($this->db->execute()) {
            // Determine unread status
            $is_read_user = 0;
            $is_read_admin = 0;

            if ($sender_id) {
                $this->db->query("SELECT role FROM users WHERE id = :sender_id");
                $this->db->bind(':sender_id', $sender_id);
                $user = $this->db->single();

                $is_read_user = ($user['role'] !== 'user') ? 0 : 1;
                $is_read_admin = ($user['role'] === 'user') ? 0 : 1;
            } else {
                // Guest reply
                $is_read_user = 1;
                $is_read_admin = 0;
            }

            $this->db->query("UPDATE messages SET status = :status, updated_at = CURRENT_TIMESTAMP, is_read_user = :is_read_user, is_read_admin = :is_read_admin WHERE id = :id");
            $this->db->bind(':status', $new_status);
            $this->db->bind(':is_read_user', $is_read_user);
            $this->db->bind(':is_read_admin', $is_read_admin);
            $this->db->bind(':id', $message_id);
            return $this->db->execute();
        }
        return false;
    }

    public function addAutoReply($message_id) {
        // Check the last message in the thread
        $this->db->query("SELECT sender_id FROM message_replies WHERE message_id = :message_id ORDER BY created_at DESC LIMIT 1");
        $this->db->bind(':message_id', $message_id);
        $lastReply = $this->db->single();

        // If the last reply exists and was from an admin/support, don't send another auto-reply
        if ($lastReply && $lastReply['sender_id'] !== null) {
            $this->db->query("SELECT role FROM users WHERE id = :sender_id");
            $this->db->bind(':sender_id', $lastReply['sender_id']);
            $user = $this->db->single();
            if ($user && ($user['role'] === 'admin' || $user['role'] === 'superadmin')) {
                return false;
            }
        }

        $autoReplyText = "Hi! This is an automated confirmation that we’ve received your message. Our support team is currently reviewing your inquiry and will get back to you within 24–48 hours. Thank you for your patience and understanding.";
        
        // Use Admin ID 1 as the default support sender
        if ($this->addReply($message_id, 1, $autoReplyText, 'active')) {
            // Get the inserted reply for UI feedback
            $replyId = $this->db->lastInsertId();
            $this->db->query("SELECT r.*, COALESCE(u.name, 'Support') as sender_name, COALESCE(u.role, 'admin') as sender_role 
                             FROM message_replies r 
                             LEFT JOIN users u ON r.sender_id = u.id 
                             WHERE r.id = :id");
            $this->db->bind(':id', $replyId);
            return $this->db->single();
        }
        return false;
    }

    public function getReplies($message_id) {
        $this->db->query("SELECT r.*, COALESCE(u.name, 'Guest') as sender_name, COALESCE(u.role, 'user') as sender_role
                         FROM message_replies r
                         LEFT JOIN users u ON r.sender_id = u.id
                         WHERE r.message_id = :message_id
                         ORDER BY r.created_at ASC");
        $this->db->bind(':message_id', $message_id);
        return $this->db->resultSet();
    }

    public function getNewReplies($message_id, $last_id) {
        $this->db->query("SELECT r.*, COALESCE(u.name, 'Guest') as sender_name, COALESCE(u.role, 'user') as sender_role
                         FROM message_replies r
                         LEFT JOIN users u ON r.sender_id = u.id
                         WHERE r.message_id = :message_id AND r.id > :last_id
                         ORDER BY r.created_at ASC");
        $this->db->bind(':message_id', $message_id);
        $this->db->bind(':last_id', $last_id);
        return $this->db->resultSet();
    }

    public function markAsReadUser($id, $user_id) {
        $this->db->query("UPDATE messages SET is_read_user = 1 WHERE id = :id AND user_id = :user_id");
        $this->db->bind(':id', $id);
        $this->db->bind(':user_id', $user_id);
        return $this->db->execute();
    }

    public function markAsReadAdmin($id) {
        $this->db->query("UPDATE messages SET is_read_admin = 1 WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    public function getUnreadCountUser($user_id) {
        $this->db->query("SELECT COUNT(*) as count FROM messages WHERE user_id = :user_id AND is_read_user = 0 AND is_archived_user = 0");
        $this->db->bind(':user_id', $user_id);
        $row = $this->db->single();
        return (int)$row['count'];
    }

    public function getUnreadCountAdmin() {
        $this->db->query("SELECT COUNT(*) as count FROM messages WHERE is_read_admin = 0 AND is_archived_admin = 0");
        $row = $this->db->single();
        return (int)$row['count'];
    }

    public function archiveAdmin($id) {
        $this->db->query("UPDATE messages SET is_archived_admin = 1 WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    public function unarchiveAdmin($id) {
        $this->db->query("UPDATE messages SET is_archived_admin = 0 WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    public function archiveUser($id, $user_id) {
        $this->db->query("UPDATE messages SET is_archived_user = 1 WHERE id = :id AND user_id = :user_id");
        $this->db->bind(':id', $id);
        $this->db->bind(':user_id', $user_id);
        return $this->db->execute();
    }

    public function unarchiveUser($id, $user_id) {
        $this->db->query("UPDATE messages SET is_archived_user = 0 WHERE id = :id AND user_id = :user_id");
        $this->db->bind(':id', $id);
        $this->db->bind(':user_id', $user_id);
        return $this->db->execute();
    }

    public function updateMessageStatus($id, $status) {
        $this->db->query("UPDATE messages SET status = :status, updated_at = CURRENT_TIMESTAMP WHERE id = :id");
        $this->db->bind(':status', $status);
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    public function deleteMessage($id) {
        $this->db->query("DELETE FROM messages WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    public function deleteMessageUser($id, $user_id) {
        $this->db->query("DELETE FROM messages WHERE id = :id AND user_id = :user_id");
        $this->db->bind(':id', $id);
        $this->db->bind(':user_id', $user_id);
        return $this->db->execute();
    }

    public function getMessageById($id) {
        $this->db->query("SELECT m.*, COALESCE(u.name, 'Guest') as user_name, COALESCE(u.email, m.email) as user_email 
                         FROM messages m 
                         LEFT JOIN users u ON m.user_id = u.id 
                         WHERE m.id = :id");
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    public function replyToMessage($id, $reply) {
        $this->db->query("UPDATE messages SET admin_reply = :reply, status = 'replied' WHERE id = :id");
        $this->db->bind(':reply', $reply);
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    public function getOpenMessagesCount() {
        $this->db->query("SELECT COUNT(*) as count FROM messages WHERE status = 'open'");
        $row = $this->db->single();
        return $row['count'];
    }

    public function closeMessage($id) {
        $this->db->query("UPDATE messages SET status = 'closed' WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    public function getMessageByTicket($ticket_number, $email) {
        // We check either the messages.email OR the users.email if user_id is set
        $this->db->query("SELECT m.*, COALESCE(u.name, 'Guest') as user_name, COALESCE(u.email, m.email) as user_email 
                         FROM messages m 
                         LEFT JOIN users u ON m.user_id = u.id 
                         WHERE m.ticket_number = :ticket_number 
                         AND (m.email = :email OR u.email = :email2)");
        $this->db->bind(':ticket_number', $ticket_number);
        $this->db->bind(':email', $email);
        $this->db->bind(':email2', $email);
        return $this->db->single();
    }
}
