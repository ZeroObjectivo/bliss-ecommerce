<?php
class FavoriteModel {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function addFavorite($userId, $productId) {
        $this->db->query("INSERT IGNORE INTO favorites (user_id, product_id) VALUES (:user_id, :product_id)");
        $this->db->bind(':user_id', $userId);
        $this->db->bind(':product_id', $productId);
        return $this->db->execute();
    }

    public function removeFavorite($userId, $productId) {
        $this->db->query("DELETE FROM favorites WHERE user_id = :user_id AND product_id = :product_id");
        $this->db->bind(':user_id', $userId);
        $this->db->bind(':product_id', $productId);
        return $this->db->execute();
    }

    public function getUserFavoriteIds($userId) {
        $this->db->query("SELECT product_id FROM favorites WHERE user_id = :user_id");
        $this->db->bind(':user_id', $userId);
        $results = $this->db->resultSet();
        
        $ids = [];
        foreach ($results as $row) {
            $ids[] = $row['product_id'];
        }
        return $ids;
    }

    public function getUserFavorites($userId) {
        $this->db->query("SELECT p.* FROM products p 
                          JOIN favorites f ON p.id = f.product_id 
                          WHERE f.user_id = :user_id 
                          ORDER BY f.created_at DESC");
        $this->db->bind(':user_id', $userId);
        return $this->db->resultSet();
    }

    public function clearUserFavorites($userId) {
        $this->db->query("DELETE FROM favorites WHERE user_id = :user_id");
        $this->db->bind(':user_id', $userId);
        return $this->db->execute();
    }
}
