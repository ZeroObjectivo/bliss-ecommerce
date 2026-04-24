<?php
class SuperAdmin extends Controller {
    public function __construct() {
        // Strict SuperAdmin Middleware
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'superadmin') {
            header("Location: /php/Webdev/public/admin");
            exit;
        }
    }

    public function products() {
        $productModel = $this->model('ProductModel');
        $data = [
            'title' => 'Manage Products',
            'products' => $productModel->getAllProducts()
        ];

        $this->view('templates/admin_header', $data);
        $this->view('admin/products', $data);
        $this->view('templates/admin_footer');
    }

    public function product_add() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $image_main = '';
            if (isset($_FILES['image_file']) && $_FILES['image_file']['error'] == 0) {
                $uploadDir = '../public/uploads/';
                if (!is_dir($uploadDir)) { mkdir($uploadDir, 0777, true); }
                $fname = time() . '_' . basename($_FILES['image_file']['name']);
                if (move_uploaded_file($_FILES['image_file']['tmp_name'], $uploadDir . $fname)) {
                    $image_main = '/php/Webdev/public/uploads/' . $fname;
                }
            }

            // Group sizes into JSON
            $sizes = [
                'US 7' => $_POST['size_7'] ?? 0,
                'US 8' => $_POST['size_8'] ?? 0,
                'US 9' => $_POST['size_9'] ?? 0,
                'US 10' => $_POST['size_10'] ?? 0,
                'US 11' => $_POST['size_11'] ?? 0,
                'US 12' => $_POST['size_12'] ?? 0
            ];

            // Merge Status and Categories
            $status = isset($_POST['status']) ? (array)$_POST['status'] : [];
            $categories = isset($_POST['categories']) ? (array)$_POST['categories'] : [];
            $all_categories = implode(', ', array_merge($status, $categories));

            $productModel = $this->model('ProductModel');
            $product_id = $productModel->insertProduct([
                'name' => $_POST['name'],
                'description' => $_POST['description'],
                'price' => $_POST['price'],
                'category' => $all_categories,
                'brand' => $_POST['brand'],
                'sizes' => json_encode($sizes),
                'image_main' => $image_main
            ]);

            // Sync with featured_products table if Featured status is checked
            if (in_array('Featured', $status) && $product_id) {
                $productModel->addFeatured($product_id);
            }

            header("Location: /php/Webdev/public/superadmin/products");
            exit;
        }

        $data = ['title' => 'Add New Product'];
        $this->view('templates/admin_header', $data);
        $this->view('admin/product_add', $data);
        $this->view('templates/admin_footer');
    }

    public function product_edit($id) {
        $productModel = $this->model('ProductModel');
        $product = $productModel->getProductById($id);

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $image_main = $product['image_main']; // Default to existing
            if (isset($_FILES['image_file']) && $_FILES['image_file']['error'] == 0) {
                $uploadDir = '../public/uploads/';
                if (!is_dir($uploadDir)) { mkdir($uploadDir, 0777, true); }
                $fname = time() . '_' . basename($_FILES['image_file']['name']);
                if (move_uploaded_file($_FILES['image_file']['tmp_name'], $uploadDir . $fname)) {
                    $image_main = '/php/Webdev/public/uploads/' . $fname;
                }
            }

            // Group sizes into JSON
            $sizes = [
                'US 7' => $_POST['size_7'] ?? 0,
                'US 8' => $_POST['size_8'] ?? 0,
                'US 9' => $_POST['size_9'] ?? 0,
                'US 10' => $_POST['size_10'] ?? 0,
                'US 11' => $_POST['size_11'] ?? 0,
                'US 12' => $_POST['size_12'] ?? 0
            ];

            // Merge Status and Categories
            $status = isset($_POST['status']) ? (array)$_POST['status'] : [];
            $categories = isset($_POST['categories']) ? (array)$_POST['categories'] : [];
            $all_categories = implode(', ', array_merge($status, $categories));

            $productModel->updateProduct($id, [
                'name' => $_POST['name'],
                'description' => $_POST['description'],
                'price' => $_POST['price'],
                'category' => $all_categories,
                'brand' => $_POST['brand'],
                'sizes' => json_encode($sizes),
                'image_main' => $image_main
            ]);

            // Sync with featured_products table
            if (in_array('Featured', $status)) {
                // Add to featured if not already there
                $existing = $productModel->getFeaturedProducts();
                $isAlreadyFeatured = false;
                foreach($existing as $ef) { if($ef['id'] == $id) $isAlreadyFeatured = true; }
                if(!$isAlreadyFeatured) $productModel->addFeatured($id);
            } else {
                // Remove from featured
                $productModel->removeFeatured($id);
            }

            header("Location: /php/Webdev/public/superadmin/products");
            exit;
        }

        $product = $productModel->getProductById($id);
        if (!$product) {
            header("Location: /php/Webdev/public/superadmin/products");
            exit;
        }

        $data = [
            'title' => 'Edit Product',
            'product' => $product
        ];

        $this->view('templates/admin_header', $data);
        $this->view('admin/product_edit', $data);
        $this->view('templates/admin_footer');
    }

    public function product_delete($id) {
        if ($id) {
            $productModel = $this->model('ProductModel');
            $productModel->deleteProduct($id);
        }
        header("Location: /php/Webdev/public/superadmin/products");
        exit;
    }

    public function hero_settings() {
        $productModel = $this->model('ProductModel');
        $settingsModel = $this->model('SettingsModel');

        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'])) {
            $action = $_POST['action'];
            if ($action == 'add_announcement') {
                $settingsModel->addAnnouncement(trim($_POST['message']));
                header("Location: /php/Webdev/public/superadmin/hero_settings");
                exit;
            } else if ($action == 'delete_announcement') {
                $settingsModel->deleteAnnouncement($_POST['id']);
                header("Location: /php/Webdev/public/superadmin/hero_settings");
                exit;
            } else if ($action == 'toggle_announcement') {
                $settingsModel->toggleAnnouncementStatus($_POST['id'], $_POST['current_status']);
                header("Location: /php/Webdev/public/superadmin/hero_settings");
                exit;
            } else if ($action == 'update_announcement_color') {
                $settingsModel->updateSiteSetting('announcement_bg_color', $_POST['announcement_bg_color']);
                header("Location: /php/Webdev/public/superadmin/hero_settings");
                exit;
            } else if ($action == 'toggle_announcement_bar') {
                $currentStatus = $settingsModel->getSiteSetting('announcement_bar_enabled') ?? '1';
                $newStatus = $currentStatus === '1' ? '0' : '1';
                $settingsModel->updateSiteSetting('announcement_bar_enabled', $newStatus);
                header("Location: /php/Webdev/public/superadmin/hero_settings");
                exit;
            } else {
                $prod_id = $_POST['product_id'];
                if ($_POST['action'] == 'add') {
                    $productModel->clearFeatured();
                    $productModel->addFeatured($prod_id);
                } else if ($_POST['action'] == 'remove') {
                    $productModel->removeFeatured($prod_id);
                } else if ($_POST['action'] == 'update_gradient') {
                    $productModel->updateFeaturedGradient($prod_id, $_POST['bg_gradient']);
                }
                header("Location: /php/Webdev/public/superadmin/hero_settings");
                exit;
            }
        }

        $data = [
            'title' => 'Hero Settings',
            'products' => $productModel->getAllProducts(),
            'current_featured' => $productModel->getFeaturedProduct(),
            'fallbacks' => $settingsModel->getAllFallbacks(),
            'announcements' => $settingsModel->getAllAnnouncements(),
            'announcement_bg_color' => $settingsModel->getSiteSetting('announcement_bg_color') ?: '#000000',
            'announcement_bar_enabled' => $settingsModel->getSiteSetting('announcement_bar_enabled') ?? '1'
        ];
        $this->view('templates/admin_header', $data);
        $this->view('admin/hero_settings', $data);
        $this->view('templates/admin_footer');
    }

    public function fallback_add() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $settingsModel = $this->model('SettingsModel');
            $settingsModel->addFallback($_POST);
            header("Location: /php/Webdev/public/superadmin/hero_settings");
            exit;
        }

        $data = ['title' => 'Add Fallback Campaign'];
        $this->view('templates/admin_header', $data);
        $this->view('admin/fallback_add', $data);
        $this->view('templates/admin_footer');
    }

    public function fallback_edit($id) {
        $settingsModel = $this->model('SettingsModel');
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST['id'] = $id;
            $settingsModel->updateFallback($_POST);
            header("Location: /php/Webdev/public/superadmin/hero_settings");
            exit;
        }

        $fallback = $settingsModel->getFallbackById($id);
        if (!$fallback) {
            header("Location: /php/Webdev/public/superadmin/hero_settings");
            exit;
        }

        $data = [
            'title' => 'Edit Fallback Campaign',
            'fallback' => $fallback
        ];

        $this->view('templates/admin_header', $data);
        $this->view('admin/fallback_edit', $data);
        $this->view('templates/admin_footer');
    }

    public function fallback_delete($id) {
        if ($id) {
            $settingsModel = $this->model('SettingsModel');
            $settingsModel->deleteFallback($id);
        }
        header("Location: /php/Webdev/public/superadmin/hero_settings");
        exit;
    }

    public function fallback_activate($id) {
        if ($id) {
            $settingsModel = $this->model('SettingsModel');
            $settingsModel->setActiveFallback($id);
        }
        header("Location: /php/Webdev/public/superadmin/hero_settings");
        exit;
    }

    public function customer_status() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['user_id'])) {
            $userModel = $this->model('UserModel');
            $status = $_POST['current_status'] == 'active' ? 'inactive' : 'active';
            $userModel->updateUserStatus($_POST['user_id'], $status);
        }
        header("Location: /php/Webdev/public/admin/customers");
        exit;
    }

    public function customer_delete($id) {
        if ($id) {
            $userModel = $this->model('UserModel');
            $userModel->deleteUser($id);
        }
        header("Location: /php/Webdev/public/admin/customers");
        exit;
    }

    public function customer_reset_pass() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['user_id'])) {
            $userModel = $this->model('UserModel');
            
            // Validate user exists
            $user = $userModel->getUserById($_POST['user_id']);
            if (!$user) {
                header("Location: /php/Webdev/public/admin/customers");
                exit;
            }

            // Generate cryptographic token
            $token = bin2hex(random_bytes(32));

            // Set token natively into MySQL to avoid timezone drift
            $userModel->setResetToken($user['id'], $token);

            // Dynamically construct literal address based on your domain configuration (generic to localhost pathing here)
            $actualHost = $_SERVER['HTTP_HOST'] ?? 'localhost';
            $resetLink = "http://{$actualHost}/php/Webdev/public/auth/reset/{$token}";

            $data = [
                'title' => 'Password Reset Link Generated',
                'user' => $user,
                'reset_link' => $resetLink
            ];
            $this->view('templates/admin_header', $data);
            $this->view('admin/reset_link', $data);
            $this->view('templates/admin_footer');
        } else {
            header("Location: /php/Webdev/public/admin/customers");
            exit;
        }
    }
}
