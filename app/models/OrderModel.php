<?php
class OrderModel {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function getAllOrders() {
        $this->db->query("SELECT orders.*, users.name as user_name, users.email as user_email FROM orders JOIN users ON orders.user_id = users.id ORDER BY created_at DESC");
        return $this->db->resultSet();
    }

    public function getArchivedOrders() {
        $this->db->query("SELECT orders.*, users.name as user_name, users.email as user_email FROM orders JOIN users ON orders.user_id = users.id WHERE is_archived = 1 ORDER BY created_at DESC");
        return $this->db->resultSet();
    }

    public function searchOrders($keyword, $status = 'all') {
        $textKeyword = trim($keyword);
        $idKeyword = ltrim($textKeyword, '#');
        $status = strtolower(trim($status));

        $where = "WHERE (CAST(orders.id AS CHAR) LIKE :id_keyword
               OR users.name LIKE :text_keyword
               OR users.email LIKE :text_keyword)";

        if ($status === 'pending') {
            $where .= " AND (orders.status = 'pending' OR orders.status = 'processing')";
        } elseif ($status === 'returns') {
            $where .= " AND orders.status IN ('Return Requested', 'Return Approved', 'Return Rejected', 'Refunded')";
        } elseif ($status === 'returns_pending') {
            $where .= " AND orders.status = 'Return Requested'";
        } elseif ($status === 'returns_approved') {
            $where .= " AND orders.status = 'Return Approved'";
        } elseif ($status === 'returns_resolved') {
            $where .= " AND orders.status IN ('Refunded', 'Return Rejected')";
        } elseif ($status === 'all' || $status === '') {
            $where .= " AND orders.status NOT IN ('Return Requested', 'Return Approved', 'Return Rejected', 'Refunded')";
        } elseif ($status !== 'all' && $status !== '') {
            $where .= " AND orders.status = :status";
        }

        $this->db->query("
            SELECT orders.*, users.name as user_name, users.email as user_email
            FROM orders
            JOIN users ON orders.user_id = users.id
            {$where}
            ORDER BY orders.created_at DESC
        ");
        $this->db->bind(':id_keyword', '%' . $idKeyword . '%');
        $this->db->bind(':text_keyword', '%' . $textKeyword . '%');
        if ($status !== 'all' && $status !== '' && $status !== 'pending') {
            $this->db->bind(':status', $status);
        }
        return $this->db->resultSet();
    }

    public function archiveOrder($id) {
        $this->db->query("UPDATE orders SET is_archived = 1 WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    public function unarchiveOrder($id) {
        $this->db->query("UPDATE orders SET is_archived = 0 WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    public function getOrderById($id) {
        $this->db->query("SELECT orders.*, users.name as user_name, users.email as user_email FROM orders JOIN users ON orders.user_id = users.id WHERE orders.id = :id");
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    public function getOrderItems($order_id) {
        $this->db->query("SELECT order_items.*, products.name, products.image_main FROM order_items JOIN products ON order_items.product_id = products.id WHERE order_id = :id");
        $this->db->bind(':id', $order_id);
        return $this->db->resultSet();
    }

    public function updateStatus($order_id, $status) {
        $timestampField = '';
        if ($status == 'Return Approved') $timestampField = ', return_approved_at = CURRENT_TIMESTAMP';
        elseif ($status == 'Refunded') $timestampField = ', refunded_at = CURRENT_TIMESTAMP';
        elseif ($status == 'Return Rejected') $timestampField = ', return_rejected_at = CURRENT_TIMESTAMP';

        $this->db->query("UPDATE orders SET status = :status {$timestampField} WHERE id = :id");
        $this->db->bind(':status', $status);
        $this->db->bind(':id', $order_id);
        return $this->db->execute();
    }

    public function requestReturn($order_id, $reason, $image_base64 = null) {
        $this->db->query("UPDATE orders SET status = 'Return Requested', return_reason = :reason, return_image_base64 = :image, return_requested_at = CURRENT_TIMESTAMP WHERE id = :id");
        $this->db->bind(':reason', $reason);
        $this->db->bind(':image', $image_base64);
        $this->db->bind(':id', $order_id);
        return $this->db->execute();
    }

    public function createOrder($user_id, $total_price) {
        $this->db->query("INSERT INTO orders (user_id, total_price, status) VALUES (:user_id, :total_price, 'pending')");
        $this->db->bind(':user_id', $user_id);
        $this->db->bind(':total_price', $total_price);
        $this->db->execute();
        return $this->db->lastInsertId();
    }

    public function createOrderFull($data) {
        $this->db->query("INSERT INTO orders (user_id, total_price, shipping_method, payment_method, shipping_address, status) 
                          VALUES (:user_id, :total_price, :shipping_method, :payment_method, :shipping_address, 'pending')");
        
        $this->db->bind(':user_id', $data['user_id']);
        $this->db->bind(':total_price', $data['total_price']);
        $this->db->bind(':shipping_method', $data['shipping_method']);
        $this->db->bind(':payment_method', $data['payment_method']);
        $this->db->bind(':shipping_address', $data['shipping_address']);
        
        $this->db->execute();
        return $this->db->lastInsertId();
    }

    public function addOrderItem($order_id, $product_id, $size, $quantity, $price) {
        $this->db->query("INSERT INTO order_items (order_id, product_id, size, quantity, price) VALUES (:order_id, :product_id, :size, :quantity, :price)");
        $this->db->bind(':order_id', $order_id);
        $this->db->bind(':product_id', $product_id);
        $this->db->bind(':size', $size);
        $this->db->bind(':quantity', $quantity);
        $this->db->bind(':price', $price);
        return $this->db->execute();
    }

    public function getOrdersByUser($user_id) {
        $this->db->query("SELECT * FROM orders WHERE user_id = :user_id ORDER BY created_at DESC");
        $this->db->bind(':user_id', $user_id);
        return $this->db->resultSet();
    }

    public function deleteAllOrders() {
        // order_items will be deleted automatically if foreign key has ON DELETE CASCADE
        // According to setup.php, it does.
        $this->db->query("DELETE FROM orders");
        return $this->db->execute();
    }
}
