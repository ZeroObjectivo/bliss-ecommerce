<?php

class Database {
    private $host = '127.0.0.1';
    private $port = '3307';
    private $user = 'root';
    private $pass = '';
    private $dbname = 'blis_ecommerce';

    private $dbh;
    private $stmt;
    private $error;

    public function __construct() {
        // First try to connect without db name to ensure database exists
        try {
            $pdo = new PDO("mysql:host=" . $this->host . ";port=" . $this->port, $this->user, $this->pass);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->exec("CREATE DATABASE IF NOT EXISTS " . $this->dbname);
        } catch(PDOException $e) {
            $this->error = $e->getMessage();
            echo "Database Setup Error: " . $this->error;
            return;
        }

        // Now connect to the database
        $dsn = 'mysql:host=' . $this->host . ';port=' . $this->port . ';dbname=' . $this->dbname;
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
                $this->dbh->exec("ALTER TABLE messages ADD COLUMN IF NOT EXISTS email VARCHAR(255) DEFAULT NULL AFTER user_id");
                $this->dbh->exec("ALTER TABLE messages ADD COLUMN IF NOT EXISTS is_archived_user TINYINT(1) DEFAULT 0");
                $this->dbh->exec("ALTER TABLE messages ADD COLUMN IF NOT EXISTS is_archived_admin TINYINT(1) DEFAULT 0");
                $this->dbh->exec("ALTER TABLE messages ADD COLUMN IF NOT EXISTS is_read_user TINYINT(1) DEFAULT 0");
                $this->dbh->exec("ALTER TABLE messages ADD COLUMN IF NOT EXISTS is_read_admin TINYINT(1) DEFAULT 0");
                
                // Update status enum if necessary (SQL might vary, but this is a safe way to try adding new values)
                $this->dbh->exec("ALTER TABLE messages MODIFY COLUMN status ENUM('open', 'replied', 'closed', 'active', 'resolved') DEFAULT 'active'");
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

            // Patch users table if columns are missing
            try {
                $this->dbh->exec("ALTER TABLE users ADD COLUMN IF NOT EXISTS username VARCHAR(255) DEFAULT NULL AFTER name");
                $this->dbh->exec("ALTER TABLE users ADD COLUMN IF NOT EXISTS status ENUM('active', 'inactive') DEFAULT 'active' AFTER role");
                $this->dbh->exec("ALTER TABLE users ADD COLUMN IF NOT EXISTS reset_token VARCHAR(255) DEFAULT NULL");
                $this->dbh->exec("ALTER TABLE users ADD COLUMN IF NOT EXISTS reset_expires DATETIME DEFAULT NULL");
                $this->dbh->exec("ALTER TABLE users ADD COLUMN IF NOT EXISTS security_q1 VARCHAR(255) DEFAULT NULL");
                $this->dbh->exec("ALTER TABLE users ADD COLUMN IF NOT EXISTS security_a1 VARCHAR(255) DEFAULT NULL");
                $this->dbh->exec("ALTER TABLE users ADD COLUMN IF NOT EXISTS security_q2 VARCHAR(255) DEFAULT NULL");
                $this->dbh->exec("ALTER TABLE users ADD COLUMN IF NOT EXISTS security_a2 VARCHAR(255) DEFAULT NULL");
                $this->dbh->exec("ALTER TABLE users ADD COLUMN IF NOT EXISTS security_q3 VARCHAR(255) DEFAULT NULL");
                $this->dbh->exec("ALTER TABLE users ADD COLUMN IF NOT EXISTS security_a3 VARCHAR(255) DEFAULT NULL");
                $this->dbh->exec("ALTER TABLE users ADD COLUMN IF NOT EXISTS last_login DATETIME DEFAULT NULL");
            } catch(PDOException $e) {}
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
