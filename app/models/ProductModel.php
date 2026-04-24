<?php
class ProductModel {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function getAllProducts() {
        $this->db->query("SELECT p.*, CASE WHEN (fp.product_id IS NOT NULL OR p.category LIKE '%Featured%') THEN 1 ELSE 0 END as is_featured 
                         FROM products p 
                         LEFT JOIN featured_products fp ON p.id = fp.product_id 
                         ORDER BY p.created_at DESC");
        return $this->db->resultSet();
    }

    public function getProductsByCategory($category) {
        $this->db->query("SELECT p.*, CASE WHEN (fp.product_id IS NOT NULL OR p.category LIKE '%Featured%') THEN 1 ELSE 0 END as is_featured 
                         FROM products p 
                         LEFT JOIN featured_products fp ON p.id = fp.product_id 
                         WHERE p.category LIKE :category 
                         ORDER BY p.created_at DESC");
        $this->db->bind(':category', "%$category%");
        return $this->db->resultSet();
    }

    public function searchProducts($keyword) {
        $this->db->query("SELECT p.*, CASE WHEN (fp.product_id IS NOT NULL OR p.category LIKE '%Featured%') THEN 1 ELSE 0 END as is_featured 
                         FROM products p 
                         LEFT JOIN featured_products fp ON p.id = fp.product_id 
                         WHERE p.name LIKE :keyword OR p.description LIKE :keyword OR p.category LIKE :keyword");
        $this->db->bind(':keyword', "%$keyword%");
        return $this->db->resultSet();
    }

    public function insertProduct($data) {
        $this->db->query("INSERT INTO products (name, description, price, category, brand, sizes, image_main) VALUES (:name, :description, :price, :category, :brand, :sizes, :image_main)");
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':description', $data['description']);
        $this->db->bind(':price', $data['price']);
        $this->db->bind(':category', $data['category']);
        $this->db->bind(':brand', $data['brand']);
        $this->db->bind(':sizes', $data['sizes']);
        $this->db->bind(':image_main', $data['image_main']);
        
        if ($this->db->execute()) {
            return $this->db->lastInsertId();
        }
        return false;
    }

    public function updateProduct($id, $data) {
        $this->db->query("UPDATE products SET name = :name, description = :description, price = :price, category = :category, brand = :brand, sizes = :sizes, image_main = :image_main WHERE id = :id");
        $this->db->bind(':id', $id);
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':description', $data['description']);
        $this->db->bind(':price', $data['price']);
        $this->db->bind(':category', $data['category']);
        $this->db->bind(':brand', $data['brand']);
        $this->db->bind(':sizes', $data['sizes']);
        $this->db->bind(':image_main', $data['image_main']);
        return $this->db->execute();
    }

    public function subtractSizeStock($id, $size, $quantity) {
        $product = $this->getProductById($id);
        if (!$product) return false;
        
        $sizes = json_decode($product['sizes'], true);
        if (isset($sizes[$size])) {
            $sizes[$size] = max(0, $sizes[$size] - $quantity);
            return $this->updateProduct($id, [
                'name' => $product['name'],
                'description' => $product['description'],
                'price' => $product['price'],
                'category' => $product['category'],
                'brand' => $product['brand'],
                'sizes' => json_encode($sizes),
                'image_main' => $product['image_main']
            ]);
        }
        return false;
    }

    public function getTotalStock($sizesJson) {
        $sizes = json_decode($sizesJson, true);
        if (!is_array($sizes)) return 0;
        return array_sum($sizes);
    }

    public function deleteProduct($id) {
        $this->db->query("DELETE FROM products WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    public function getProductById($id) {
        $this->db->query("SELECT p.*, CASE WHEN (fp.product_id IS NOT NULL OR p.category LIKE '%Featured%') THEN 1 ELSE 0 END as is_featured 
                         FROM products p 
                         LEFT JOIN featured_products fp ON p.id = fp.product_id 
                         WHERE p.id = :id");
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    // --- FEATURED PRODUCTS LOGIC ---
    public function getFeaturedProducts($limit = null) {
        // Unified logic: Get products either in featured_products table OR tagged 'Featured' in category
        $sql = "SELECT p.*, fp.bg_gradient, 1 as is_featured 
                FROM products p 
                LEFT JOIN featured_products fp ON p.id = fp.product_id 
                WHERE (fp.product_id IS NOT NULL OR p.category LIKE '%Featured%')
                ORDER BY fp.id DESC, p.created_at DESC";
        if ($limit) $sql .= " LIMIT :limit";
        $this->db->query($sql);
        if ($limit) $this->db->bind(':limit', $limit);
        return $this->db->resultSet();
    }

    public function getFeaturedProduct() {
        // Hero logic: Usually just the latest specifically marked in the table
        $this->db->query("SELECT p.*, fp.bg_gradient, 1 as is_featured 
                         FROM products p 
                         JOIN featured_products fp ON p.id = fp.product_id 
                         ORDER BY fp.id DESC LIMIT 1");
        return $this->db->single();
    }

    public function clearFeatured() {
        $this->db->query("DELETE FROM featured_products");
        return $this->db->execute();
    }

    public function addFeatured($product_id) {
        $this->db->query("INSERT INTO featured_products (product_id, bg_gradient) VALUES (:id, 'linear-gradient(135deg, #0f172a 0%, #334155 100%)')");
        $this->db->bind(':id', $product_id);
        return $this->db->execute();
    }

    public function removeFeatured($product_id) {
        $this->db->query("DELETE FROM featured_products WHERE product_id = :id");
        $this->db->bind(':id', $product_id);
        return $this->db->execute();
    }

    public function getNewArrivals($limit = null) {
        // Strictly filter products with 'New Arrival' in category string
        $sql = "SELECT p.*, CASE WHEN (fp.product_id IS NOT NULL OR p.category LIKE '%Featured%') THEN 1 ELSE 0 END as is_featured 
                FROM products p 
                LEFT JOIN featured_products fp ON p.id = fp.product_id 
                WHERE p.category LIKE '%New Arrival%'
                ORDER BY p.created_at DESC";
        if ($limit) $sql .= " LIMIT :limit";
        $this->db->query($sql);
        if ($limit) $this->db->bind(':limit', $limit);
        return $this->db->resultSet();
    }

    public function getBestSellers($limit = null) {
        // Strictly filter products with 'Best Seller' in category string
        $sql = "SELECT p.*, COUNT(oi.product_id) as sales_count, 
                CASE WHEN (fp.product_id IS NOT NULL OR p.category LIKE '%Featured%') THEN 1 ELSE 0 END as is_featured 
                FROM products p 
                LEFT JOIN order_items oi ON p.id = oi.product_id 
                LEFT JOIN featured_products fp ON p.id = fp.product_id 
                WHERE p.category LIKE '%Best Seller%'
                GROUP BY p.id 
                ORDER BY sales_count DESC";
        if ($limit) $sql .= " LIMIT :limit";
        $this->db->query($sql);
        if ($limit) $this->db->bind(':limit', $limit);
        return $this->db->resultSet();
    }
}
