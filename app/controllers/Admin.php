<?php
class Admin extends Controller {
    public function __construct() {
        // Auto-bridge customer session to admin session if user is admin/superadmin
        if (!isset($_SESSION['admin_id']) && isset($_SESSION['user_id']) && isset($_SESSION['user_role'])) {
            if ($_SESSION['user_role'] === 'admin' || $_SESSION['user_role'] === 'superadmin') {
                $_SESSION['admin_id'] = $_SESSION['user_id'];
                $_SESSION['admin_name'] = $_SESSION['user_name'];
                $_SESSION['admin_role'] = $_SESSION['user_role'];
            }
        }

        // Auth Middleware ensuring only admins can enter using separated admin session
        if (!isset($_SESSION['admin_id']) || ($_SESSION['admin_role'] != 'admin' && $_SESSION['admin_role'] != 'superadmin')) {
            header("Location: /php/Webdev/public/adminauth/login");
            exit;
        }
    }

    public function index() {
        $productModel = $this->model('ProductModel');
        $products = $productModel->getAllProducts();
        
        $orderModel = $this->model('OrderModel');
        $allOrders = $orderModel->getAllOrders();
        
        $userModel = $this->model('UserModel');
        $customers = $userModel->getAllUsers();
        
        $totalRevenue = 0;
        $pendingCount = 0;
        $revenueByCategory = [];

        foreach($allOrders as $o) {
            if ($o['status'] != 'cancelled') {
                $totalRevenue += (float)$o['total_price'];
                
                // Detailed Revenue by category (rough estimation from order items)
                $items = $orderModel->getOrderItems($o['id']);
                foreach($items as $item) {
                    $prod = $productModel->getProductById($item['product_id']);
                    if ($prod) {
                        // Use the first category found
                        $cats = explode(',', $prod['category']);
                        $mainCat = trim($cats[0]);
                        if (!isset($revenueByCategory[$mainCat])) $revenueByCategory[$mainCat] = 0;
                        $revenueByCategory[$mainCat] += ($item['price'] * $item['quantity']);
                    }
                }
            }
            if ($o['status'] == 'pending' || $o['status'] == 'processing') {
                $pendingCount++;
            }
        }

        // Recent Orders (Last 5)
        $recentOrders = array_slice($allOrders, 0, 5);

        // Top Selling Products (calculated from sales count)
        $topSelling = $productModel->getBestSellers(5);

        // Low Stock Items (Any size < 5)
        $lowStockItems = [];
        foreach($products as $p) {
            $sizes = json_decode($p['sizes'], true);
            $isLow = false;
            if (is_array($sizes)) {
                foreach($sizes as $size => $qty) {
                    if ($qty < 5) {
                        $isLow = true;
                        break;
                    }
                }
            }
            if ($isLow) {
                $lowStockItems[] = $p;
            }
        }

        // Recent Customers (Last 5 registered)
        $recentCustomers = array_slice($customers, 0, 5);

        $data = [
            'title' => 'Admin Dashboard',
            'total_products' => count($products),
            'total_orders' => count($allOrders),
            'total_revenue' => $totalRevenue,
            'total_customers' => count($customers),
            'pending_orders_count' => $pendingCount,
            'recent_orders' => $recentOrders,
            'low_stock_items' => array_slice($lowStockItems, 0, 5),
            'top_selling' => $topSelling,
            'revenue_by_category' => $revenueByCategory,
            'recent_customers' => $recentCustomers
        ];

        $this->view('templates/admin_header', $data);
        $this->view('admin/dashboard', $data);
        $this->view('templates/admin_footer');
    }

    public function customers() {
        $userModel = $this->model('UserModel');
        $allUsers = $userModel->getAllUsers();
        
        $active = array_values(array_filter($allUsers, fn($u) => $u['status'] == 'active'));
        $suspended = array_values(array_filter($allUsers, fn($u) => $u['status'] == 'inactive'));

        $data = [
            'title' => 'Customer Management',
            'all' => $allUsers,
            'active' => $active,
            'suspended' => $suspended
        ];
        $this->view('templates/admin_header', $data);
        $this->view('admin/customers', $data);
        $this->view('templates/admin_footer');
    }

    public function orders() {
        $orderModel = $this->model('OrderModel');
        $allOrders = $orderModel->getAllOrders();

        $pending = array_values(array_filter($allOrders, fn($o) => ($o['status'] == 'pending' || $o['status'] == 'processing')));
        $shipped = array_values(array_filter($allOrders, fn($o) => $o['status'] == 'shipped'));
        $delivered = array_values(array_filter($allOrders, fn($o) => $o['status'] == 'delivered'));
        $completed = array_values(array_filter($allOrders, fn($o) => $o['status'] == 'completed'));
        $cancelled = array_values(array_filter($allOrders, fn($o) => $o['status'] == 'cancelled'));

        $data = [
            'title' => 'Order Management',
            'all' => $allOrders,
            'pending' => $pending,
            'shipped' => $shipped,
            'delivered' => $delivered,
            'completed' => $completed,
            'cancelled' => $cancelled
        ];
        $this->view('templates/admin_header', $data);
        $this->view('admin/orders', $data);
        $this->view('templates/admin_footer');
    }
    public function order_detail($id) {
        $orderModel = $this->model('OrderModel');
        $order = $orderModel->getOrderById($id);
        if (!$order) {
            header("Location: /php/Webdev/public/admin/orders");
            exit;
        }

        $data = [
            'title' => 'Order #' . $id,
            'order' => $order,
            'items' => $orderModel->getOrderItems($id)
        ];
        $this->view('templates/admin_header', $data);
        $this->view('admin/order_detail', $data);
        $this->view('templates/admin_footer');
    }

    public function order_update() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['order_id']) && isset($_POST['status'])) {
            $orderModel = $this->model('OrderModel');
            $status = $_POST['status'];
            $order_id = $_POST['order_id'];

            // Server-side safety: Don't allow updates if order is already terminal or delivered
            $currentOrder = $orderModel->getOrderById($order_id);
            if ($currentOrder && ($currentOrder['status'] == 'delivered' || $currentOrder['status'] == 'completed' || $currentOrder['status'] == 'cancelled')) {
                header("Location: /php/Webdev/public/admin/orders?error=order_finalized");
                exit;
            }

            $orderModel->updateStatus($order_id, $status);
            
            // Auto-archive if completed or cancelled, unarchive otherwise
            if ($status == 'completed' || $status == 'cancelled') {
                $orderModel->archiveOrder($order_id);
            } else {
                $orderModel->unarchiveOrder($order_id);
            }
        }
        header("Location: /php/Webdev/public/admin/orders");
        exit;
    }

    public function orders_clear_all() {
        $orderModel = $this->model('OrderModel');
        if ($orderModel->deleteAllOrders()) {
            header("Location: /php/Webdev/public/admin/orders?success=all_orders_deleted");
        } else {
            header("Location: /php/Webdev/public/admin/orders?error=delete_failed");
        }
        exit;
    }

    public function inbox() {
        $messageModel = $this->model('MessageModel');
        $messages = $messageModel->getAllMessages(true);
        
        // Attach replies to each message for the detail view
        foreach ($messages as &$msg) {
            $msg['replies'] = $messageModel->getReplies($msg['id']);
        }

        $data = [
            'title' => 'Inbox',
            'messages' => $messages
        ];

        $this->view('templates/admin_header', $data);
        $this->view('admin/inbox', $data);
        $this->view('templates/admin_footer');
    }

    public function reply() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $messageModel = $this->model('MessageModel');
            $id = $_POST['id'];
            $reply = trim($_POST['reply']);
            $sender_id = $_SESSION['admin_id'];

            if ($messageModel->addReply($id, $sender_id, $reply, 'active')) {
                if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                    echo json_encode(['success' => true, 'message' => 'reply_sent', 'reply_text' => $reply, 'created_at' => date('Y-m-d H:i:s')]);
                    exit;
                }
                header('Location: /php/Webdev/public/admin/inbox?success=Reply_sent');
            } else {
                if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                    echo json_encode(['success' => false, 'message' => 'failed_to_send_reply']);
                    exit;
                }
                header('Location: /php/Webdev/public/admin/inbox?error=Failed_to_send_reply');
            }
        }
    }

    public function fetch_new_replies($ticket_id, $last_id) {
        $messageModel = $this->model('MessageModel');
        $replies = $messageModel->getNewReplies($ticket_id, $last_id);
        echo json_encode(['success' => true, 'replies' => $replies]);
        exit;
    }

    public function mark_as_read($id) {
        $messageModel = $this->model('MessageModel');
        $messageModel->markAsReadAdmin($id);
        echo json_encode(['success' => true]);
        exit;
    }

    public function message_archive($id) {
        $messageModel = $this->model('MessageModel');
        if ($messageModel->archiveAdmin($id)) {
            header('Location: /php/Webdev/public/admin/inbox?success=Message_archived');
        } else {
            header('Location: /php/Webdev/public/admin/inbox?error=Archive_failed');
        }
    }

    public function message_unarchive($id) {
        $messageModel = $this->model('MessageModel');
        if ($messageModel->unarchiveAdmin($id)) {
            header('Location: /php/Webdev/public/admin/inbox?success=Message_unarchived');
        } else {
            header('Location: /php/Webdev/public/admin/inbox?error=Unarchive_failed');
        }
    }

    public function message_status() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $messageModel = $this->model('MessageModel');
            $id = $_POST['id'];
            $status = $_POST['status'];
            if ($messageModel->updateMessageStatus($id, $status)) {
                header('Location: /php/Webdev/public/admin/inbox?success=Status_updated');
            } else {
                header('Location: /php/Webdev/public/admin/inbox?error=Status_update_failed');
            }
        }
    }

    public function message_delete($id) {
        $messageModel = $this->model('MessageModel');
        if ($messageModel->deleteMessage($id)) {
            header('Location: /php/Webdev/public/admin/inbox?success=Message_deleted_permanently');
        } else {
            header('Location: /php/Webdev/public/admin/inbox?error=Delete_failed');
        }
    }
}
