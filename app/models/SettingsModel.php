<?php

class SettingsModel {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function getAllFallbacks() {
        $this->db->query("SELECT * FROM homepage_fallbacks ORDER BY id DESC");
        return $this->db->resultSet();
    }

    public function getFallbackById($id) {
        $this->db->query("SELECT * FROM homepage_fallbacks WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    public function getActiveFallback() {
        $this->db->query("SELECT * FROM homepage_fallbacks WHERE is_active = 1 LIMIT 1");
        return $this->db->single();
    }

    public function addFallback($data) {
        $this->db->query("INSERT INTO homepage_fallbacks (campaign_name, badge_text, hero_title, hero_subtitle, tagline, description, category_pill, action_headline, btn1_text, btn1_link, btn2_text, btn2_link, num_buttons, bg_gradient) VALUES (:campaign_name, :badge_text, :hero_title, :hero_subtitle, :tagline, :description, :category_pill, :action_headline, :btn1_text, :btn1_link, :btn2_text, :btn2_link, :num_buttons, :bg_gradient)");
        
        $this->db->bind(':campaign_name', $data['campaign_name']);
        $this->db->bind(':badge_text', $data['badge_text']);
        $this->db->bind(':hero_title', $data['hero_title']);
        $this->db->bind(':hero_subtitle', $data['hero_subtitle']);
        $this->db->bind(':tagline', $data['tagline']);
        $this->db->bind(':description', $data['description']);
        $this->db->bind(':category_pill', $data['category_pill']);
        $this->db->bind(':action_headline', $data['action_headline']);
        $this->db->bind(':btn1_text', $data['btn1_text']);
        $this->db->bind(':btn1_link', $data['btn1_link']);
        $this->db->bind(':btn2_text', $data['btn2_text']);
        $this->db->bind(':btn2_link', $data['btn2_link']);
        $this->db->bind(':num_buttons', $data['num_buttons'] ?? 2);
        $this->db->bind(':bg_gradient', $data['bg_gradient']);

        return $this->db->execute();
    }

    public function updateFallback($data) {
        $this->db->query("UPDATE homepage_fallbacks SET campaign_name = :campaign_name, badge_text = :badge_text, hero_title = :hero_title, hero_subtitle = :hero_subtitle, tagline = :tagline, description = :description, category_pill = :category_pill, action_headline = :action_headline, btn1_text = :btn1_text, btn1_link = :btn1_link, btn2_text = :btn2_text, btn2_link = :btn2_link, num_buttons = :num_buttons, bg_gradient = :bg_gradient WHERE id = :id");
        
        $this->db->bind(':id', $data['id']);
        $this->db->bind(':campaign_name', $data['campaign_name']);
        $this->db->bind(':badge_text', $data['badge_text']);
        $this->db->bind(':hero_title', $data['hero_title']);
        $this->db->bind(':hero_subtitle', $data['hero_subtitle']);
        $this->db->bind(':tagline', $data['tagline']);
        $this->db->bind(':description', $data['description']);
        $this->db->bind(':category_pill', $data['category_pill']);
        $this->db->bind(':action_headline', $data['action_headline']);
        $this->db->bind(':btn1_text', $data['btn1_text']);
        $this->db->bind(':btn1_link', $data['btn1_link']);
        $this->db->bind(':btn2_text', $data['btn2_text']);
        $this->db->bind(':btn2_link', $data['btn2_link']);
        $this->db->bind(':num_buttons', $data['num_buttons'] ?? 2);
        $this->db->bind(':bg_gradient', $data['bg_gradient']);

        return $this->db->execute();
    }

    public function deleteFallback($id) {
        $this->db->query("DELETE FROM homepage_fallbacks WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    public function setActiveFallback($id) {
        // First, deactivate all
        $this->db->query("UPDATE homepage_fallbacks SET is_active = 0");
        $this->db->execute();

        // Then activate one
        $this->db->query("UPDATE homepage_fallbacks SET is_active = 1 WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    // --- Announcements ---
    public function getAllAnnouncements() {
        $this->db->query("SELECT * FROM announcements ORDER BY id ASC");
        return $this->db->resultSet();
    }

    public function getActiveAnnouncements() {
        $this->db->query("SELECT * FROM announcements WHERE is_active = 1 ORDER BY id ASC");
        return $this->db->resultSet();
    }

    public function addAnnouncement($message) {
        $this->db->query("INSERT INTO announcements (message) VALUES (:message)");
        $this->db->bind(':message', $message);
        return $this->db->execute();
    }

    public function deleteAnnouncement($id) {
        $this->db->query("DELETE FROM announcements WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    public function toggleAnnouncementStatus($id, $current_status) {
        $new_status = $current_status == 1 ? 0 : 1;
        $this->db->query("UPDATE announcements SET is_active = :is_active WHERE id = :id");
        $this->db->bind(':is_active', $new_status);
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    // --- General Site Settings ---
    public function getSiteSetting($key) {
        $this->db->query("SELECT setting_value FROM site_settings WHERE setting_key = :key");
        $this->db->bind(':key', $key);
        $result = $this->db->single();
        return $result ? $result['setting_value'] : null;
    }

    public function updateSiteSetting($key, $value) {
        $this->db->query("INSERT INTO site_settings (setting_key, setting_value) VALUES (:key, :value) ON DUPLICATE KEY UPDATE setting_value = :value2");
        $this->db->bind(':key', $key);
        $this->db->bind(':value', $value);
        $this->db->bind(':value2', $value);
        return $this->db->execute();
    }
}
