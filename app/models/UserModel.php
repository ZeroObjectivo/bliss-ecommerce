<?php
class UserModel {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function getAllUsers() {
        $this->db->query("SELECT id, name, email, role, status, created_at FROM users WHERE role = 'user' ORDER BY created_at DESC");
        return $this->db->resultSet();
    }

    public function updateUserStatus($id, $status) {
        $this->db->query("UPDATE users SET status = :status WHERE id = :id");
        $this->db->bind(':status', $status);
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    public function deleteUser($id) {
        $this->db->query("DELETE FROM users WHERE id = :id AND role = 'user'");
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    public function getUserById($id) {
        $this->db->query("SELECT * FROM users WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    public function setResetToken($id, $token) {
        $this->db->query("UPDATE users SET reset_token = :token, reset_expires = DATE_ADD(NOW(), INTERVAL 2 HOUR) WHERE id = :id");
        $this->db->bind(':token', $token);
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    public function getUserByToken($token) {
        $this->db->query("SELECT * FROM users WHERE reset_token = :token AND reset_expires > NOW()");
        $this->db->bind(':token', $token);
        return $this->db->single();
    }

    public function clearResetToken($id) {
        $this->db->query("UPDATE users SET reset_token = NULL, reset_expires = NULL WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    public function resetUserPassword($id, $hashedPassword) {
        $this->db->query("UPDATE users SET password = :password, reset_token = NULL, reset_expires = NULL WHERE id = :id");
        $this->db->bind(':password', $hashedPassword);
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    public function updateProfile($id, $data) {
        $this->db->query("UPDATE users SET name = :name, username = :username WHERE id = :id");
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':username', $data['username']);
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    public function updateProfilePicture($id, $path) {
        $this->db->query("UPDATE users SET profile_picture = :path WHERE id = :id");
        $this->db->bind(':path', $path);
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    public function getAddresses($userId) {
        $this->db->query("SELECT * FROM user_addresses WHERE user_id = :user_id ORDER BY is_default DESC, created_at DESC");
        $this->db->bind(':user_id', $userId);
        return $this->db->resultSet();
    }

    public function addAddress($data) {
        // If this is the first address, make it default
        $addresses = $this->getAddresses($data['user_id']);
        $is_default = count($addresses) == 0 ? 1 : 0;
        $category = isset($data['category']) ? $data['category'] : 'Home Address';

        $this->db->query("INSERT INTO user_addresses (user_id, street_address, city, province, postal_code, is_default, category) VALUES (:user_id, :street_address, :city, :province, :postal_code, :is_default, :category)");
        $this->db->bind(':user_id', $data['user_id']);
        $this->db->bind(':street_address', $data['street_address']);
        $this->db->bind(':city', $data['city']);
        $this->db->bind(':province', $data['province']);
        $this->db->bind(':postal_code', $data['postal_code']);
        $this->db->bind(':is_default', $is_default);
        $this->db->bind(':category', $category);
        return $this->db->execute();
    }

    public function updateAddress($data) {
        $this->db->query("UPDATE user_addresses SET street_address = :street_address, city = :city, province = :province, postal_code = :postal_code, category = :category WHERE id = :id AND user_id = :user_id");
        $this->db->bind(':street_address', $data['street_address']);
        $this->db->bind(':city', $data['city']);
        $this->db->bind(':province', $data['province']);
        $this->db->bind(':postal_code', $data['postal_code']);
        $this->db->bind(':category', $data['category']);
        $this->db->bind(':id', $data['id']);
        $this->db->bind(':user_id', $data['user_id']);
        return $this->db->execute();
    }

    public function deleteAddress($id, $userId) {
        $this->db->query("DELETE FROM user_addresses WHERE id = :id AND user_id = :user_id");
        $this->db->bind(':id', $id);
        $this->db->bind(':user_id', $userId);
        return $this->db->execute();
    }

    public function setDefaultAddress($id, $userId) {
        // Reset all to 0
        $this->db->query("UPDATE user_addresses SET is_default = 0 WHERE user_id = :user_id");
        $this->db->bind(':user_id', $userId);
        $this->db->execute();

        // Set specific to 1
        $this->db->query("UPDATE user_addresses SET is_default = 1 WHERE id = :id AND user_id = :user_id");
        $this->db->bind(':id', $id);
        $this->db->bind(':user_id', $userId);
        return $this->db->execute();
    }

    public function updateLastLogin($userId) {
        $this->db->query("UPDATE users SET last_login = NOW() WHERE id = :id");
        $this->db->bind(':id', $userId);
        return $this->db->execute();
    }

    public function updateSecurityQuestions($id, $data) {
        $this->db->query("UPDATE users SET 
                         security_q1 = :q1, security_a1 = :a1, 
                         security_q2 = :q2, security_a2 = :a2, 
                         security_q3 = :q3, security_a3 = :a3 
                         WHERE id = :id");
        $this->db->bind(':q1', $data['security_q1']);
        $this->db->bind(':a1', $data['security_a1']);
        $this->db->bind(':q2', $data['security_q2']);
        $this->db->bind(':a2', $data['security_a2']);
        $this->db->bind(':q3', $data['security_q3']);
        $this->db->bind(':a3', $data['security_a3']);
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }
}
