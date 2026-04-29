<?php

class Database {
    private $host = '127.0.0.1';
    private $user = 'root';
    private $pass = '';
    private $dbname = 'bliss_ecommerce';

    private $dbh;
    private $stmt;
    private $error;

    public function __construct() {
        // First try to connect without db name to ensure database exists
        try {
            $pdo = new PDO("mysql:host=" . $this->host, $this->user, $this->pass);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->exec("CREATE DATABASE IF NOT EXISTS " . $this->dbname);
        } catch(PDOException $e) {
            $this->error = $e->getMessage();
            echo "Database Setup Error: " . $this->error;
            return;
        }

        // Now connect to the database
        $dsn = 'mysql:host=' . $this->host . ';dbname=' . $this->dbname;
        $options = array(
            PDO::ATTR_PERSISTENT => true,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        );

        try {
            $this->dbh = new PDO($dsn, $this->user, $this->pass, $options);
            
            // Auto-create messages table if not exists
            $this->dbh->exec("CREATE TABLE IF NOT EXISTS messages (
                id INT AUTO_INCREMENT PRIMARY KEY,
                user_id INT NOT NULL,
                subject VARCHAR(255) NOT NULL,
                message TEXT NOT NULL,
                status ENUM('open', 'replied', 'closed') DEFAULT 'open',
                admin_reply TEXT,
                is_archived_user TINYINT(1) DEFAULT 0,
                is_archived_admin TINYINT(1) DEFAULT 0,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
            )");

            // Patch for existing tables missing the archive columns
            try {
                $this->dbh->exec("ALTER TABLE messages ADD COLUMN IF NOT EXISTS ticket_number VARCHAR(50) UNIQUE AFTER user_id");
                $this->dbh->exec("ALTER TABLE messages ADD COLUMN IF NOT EXISTS is_archived_user TINYINT(1) DEFAULT 0");
                $this->dbh->exec("ALTER TABLE messages ADD COLUMN IF NOT EXISTS is_archived_admin TINYINT(1) DEFAULT 0");
            } catch(PDOException $e) {}

            // Create message_replies table
            $this->dbh->exec("CREATE TABLE IF NOT EXISTS message_replies (
                id INT AUTO_INCREMENT PRIMARY KEY,
                message_id INT NOT NULL,
                sender_id INT NOT NULL,
                reply_text TEXT NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (message_id) REFERENCES messages(id) ON DELETE CASCADE,
                FOREIGN KEY (sender_id) REFERENCES users(id) ON DELETE CASCADE
            )");
        } catch(PDOException $e) {
            $this->error = $e->getMessage();
            echo "Connection Error: " . $this->error;
        }
    }

    public function query($sql) {
        $this->stmt = $this->dbh->prepare($sql);
    }

    public function bind($param, $value, $type = null) {
        if (is_null($type)) {
            switch (true) {
                case is_int($value):
                    $type = PDO::PARAM_INT;
                    break;
                case is_bool($value):
                    $type = PDO::PARAM_BOOL;
                    break;
                case is_null($value):
                    $type = PDO::PARAM_NULL;
                    break;
                default:
                    $type = PDO::PARAM_STR;
            }
        }
        $this->stmt->bindValue($param, $value, $type);
    }

    public function execute() {
        return $this->stmt->execute();
    }

    public function resultSet() {
        $this->execute();
        return $this->stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function single() {
        $this->execute();
        return $this->stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function rowCount() {
        return $this->stmt->rowCount();
    }

    public function lastInsertId() {
        return $this->dbh->lastInsertId();
    }
}
