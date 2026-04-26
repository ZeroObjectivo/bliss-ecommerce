<?php
class User {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function findUserByEmail($email) {
        $this->db->query("SELECT * FROM users WHERE email = :email");
        $this->db->bind(':email', $email);
        return $this->db->single();
    }

    public function register($data) {
        $this->db->query("INSERT INTO users (name, email, password, security_q1, security_a1, security_q2, security_a2, security_q3, security_a3) VALUES (:name, :email, :password, :q1, :a1, :q2, :a2, :q3, :a3)");
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':password', $data['password']);
        $this->db->bind(':q1', $data['security_q1']);
        $this->db->bind(':a1', $data['security_a1']);
        $this->db->bind(':q2', $data['security_q2']);
        $this->db->bind(':a2', $data['security_a2']);
        $this->db->bind(':q3', $data['security_q3']);
        $this->db->bind(':a3', $data['security_a3']);
        return $this->db->execute();
    }
}
