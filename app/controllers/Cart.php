<?php
class Cart extends Controller {
    public function index() {
        $cartItems = [];
        $total = 0;

        if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
            $productModel = $this->model('ProductModel');
            
            foreach ($_SESSION['cart'] as $cartKey => $quantity) {
                // cartKey is "productID_size"
                $parts = explode('_', $cartKey);
                $id = $parts[0];
                $size = $parts[1] ?? 'Universal';

                $product = $productModel->getProductById($id);
                if ($product) {
                    $sizes = json_decode($product['sizes'], true);
                    $stock = $sizes[$size] ?? 0;
                    
                    $subtotal = $product['price'] * $quantity;
                    $total += $subtotal;
                    $cartItems[] = [
                        'product' => $product,
                        'size' => $size,
                        'quantity' => $quantity,
                        'subtotal' => $subtotal,
                        'cart_key' => $cartKey,
                        'stock' => $stock
                    ];
                }
            }
        }

        $data = [
            'title' => 'Your Cart',
            'items' => $cartItems,
            'total' => $total
        ];

        $this->view('templates/header', $data);
        $this->view('cart/index', $data);
        $this->view('templates/footer');
    }

    public function add() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['product_id'])) {
            $id = $_POST['product_id'];
            $size = $_POST['size'] ?? '';
            $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;
            
            // Handle Edit Mode (from Cart)
            $isEdit = isset($_POST['is_edit']) && $_POST['is_edit'] == '1';
            $oldKey = $_POST['old_key'] ?? '';

            if (empty($size)) {
                if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                    echo json_encode(['success' => false, 'error' => 'Please select a size.']);
                    exit;
                }
                header("Location: " . ($_SERVER['HTTP_REFERER'] ?? "/php/Webdev/public/cart"));
                exit;
            }
            
            $cartKey = $id . '_' . $size;

            if (!isset($_SESSION['cart'])) {
                $_SESSION['cart'] = [];
            }

            // Check Stock
            $productModel = $this->model('ProductModel');
            $product = $productModel->getProductById($id);
            if (!$product) {
                if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                    echo json_encode(['success' => false, 'error' => 'Product not found.']);
                    exit;
                }
                header("Location: /php/Webdev/public/cart");
                exit;
            }

            $sizes = json_decode($product['sizes'], true);
            $available = $sizes[$size] ?? 0;

            $newQty = $quantity;
            if ($isEdit && !empty($oldKey) && isset($_SESSION['cart'][$oldKey])) {
                // If editing, we take the quantity from the old item
                $newQty = $_SESSION['cart'][$oldKey];
                // If the target size already exists in cart (and it's a different key), we must sum them to check stock
                if ($oldKey !== $cartKey && isset($_SESSION['cart'][$cartKey])) {
                    $newQty += $_SESSION['cart'][$cartKey];
                }
            } else if (isset($_SESSION['cart'][$cartKey])) {
                // If adding more of the same, sum them up
                $newQty = $_SESSION['cart'][$cartKey] + $quantity;
            }

            if ($newQty > $available) {
                if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                    $msg = $available <= 0 ? "This size is now out of stock." : "Only " . $available . " items in stock for this size.";
                    if (isset($_SESSION['cart'][$cartKey]) && !$isEdit) {
                        $inCart = $_SESSION['cart'][$cartKey];
                        $msg = "You already have $inCart in cart. Only $available available.";
                    }
                    echo json_encode(['success' => false, 'error' => $msg]);
                    exit;
                }
                header("Location: " . ($_SERVER['HTTP_REFERER'] ?? "/php/Webdev/public/cart") . "?error=insufficient_stock");
                exit;
            }

            if ($isEdit && !empty($oldKey) && isset($_SESSION['cart'][$oldKey])) {
                $existingQty = $_SESSION['cart'][$oldKey];
                unset($_SESSION['cart'][$oldKey]);
                
                // Add to new key (merging if same size already exists)
                if (isset($_SESSION['cart'][$cartKey])) {
                    $_SESSION['cart'][$cartKey] += $existingQty;
                } else {
                    $_SESSION['cart'][$cartKey] = $existingQty;
                }
            } else {
                // Standard Add
                if (isset($_SESSION['cart'][$cartKey])) {
                    $_SESSION['cart'][$cartKey] += $quantity;
                } else {
                    $_SESSION['cart'][$cartKey] = $quantity;
                }
            }

            // Handle Move from Favorites
            if (isset($_POST['remove_from_favorites']) && $_POST['remove_from_favorites'] == '1' && isset($_SESSION['user_id'])) {
                $favoriteModel = $this->model('FavoriteModel');
                $favoriteModel->removeFavorite($_SESSION['user_id'], $id);
                if (isset($_SESSION['favorites_list'])) {
                    $_SESSION['favorites_list'] = array_diff($_SESSION['favorites_list'], [$id]);
                }
            }

            if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                $cartCount = array_sum($_SESSION['cart']);
                echo json_encode(['success' => true, 'cartCount' => $cartCount]);
                exit;
            }

            header("Location: /php/Webdev/public/cart");
            exit;
        }
    }

    public function update_quantity() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['key']) && isset($_POST['qty'])) {
            $cartKey = $_POST['key'];
            $qty = (int)$_POST['qty'];

            if ($qty < 1) {
                echo json_encode(['success' => false, 'error' => 'Quantity must be at least 1']);
                exit;
            }

            // Check stock
            $parts = explode('_', $cartKey);
            $id = $parts[0];
            $size = $parts[1] ?? 'Universal';

            $productModel = $this->model('ProductModel');
            $product = $productModel->getProductById($id);
            if ($product) {
                $sizes = json_decode($product['sizes'], true);
                $available = $sizes[$size] ?? 0;

                if ($qty > $available) {
                    echo json_encode(['success' => false, 'error' => 'Insufficient stock']);
                    exit;
                }

                $_SESSION['cart'][$cartKey] = $qty;
                echo json_encode(['success' => true]);
                exit;
            }
        }
        echo json_encode(['success' => false]);
        exit;
    }

    public function remove() {
        $cartKey = $_GET['key'] ?? '';
        if (isset($_SESSION['cart'][$cartKey])) {
            unset($_SESSION['cart'][$cartKey]);
        }
        header("Location: /php/Webdev/public/cart");
        exit;
    }

    public function clear() {
        if (isset($_SESSION['cart'])) {
            unset($_SESSION['cart']);
        }
        header("Location: /php/Webdev/public/cart");
        exit;
    }

    public function add_address() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (!isset($_SESSION['user_id'])) {
                header("Location: /php/Webdev/public/auth/login");
                exit;
            }

            $userModel = $this->model('UserModel');
            $data = [
                'user_id' => $_SESSION['user_id'],
                'street_address' => trim($_POST['street_address']),
                'city' => trim($_POST['city']),
                'province' => trim($_POST['province']),
                'postal_code' => trim($_POST['postal_code']),
                'category' => trim($_POST['category'] ?? 'Home Address')
            ];

            if ($userModel->addAddress($data)) {
                header("Location: /php/Webdev/public/cart/checkout?success=address_added");
            } else {
                header("Location: /php/Webdev/public/cart/checkout?error=address_failed");
            }
            exit;
        }
    }

    public function checkout() {
        if (!isset($_SESSION['user_id'])) {
            header("Location: /php/Webdev/public/auth/login");
            exit;
        }

        if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
            header("Location: /php/Webdev/public/cart");
            exit;
        }

        $cartItems = [];
        $total = 0;
        $productModel = $this->model('ProductModel');
        $userModel = $this->model('UserModel');

        foreach ($_SESSION['cart'] as $cartKey => $quantity) {
            $parts = explode('_', $cartKey);
            $id = $parts[0];
            $size = $parts[1] ?? 'Universal';
            $product = $productModel->getProductById($id);
            if ($product) {
                $subtotal = $product['price'] * $quantity;
                $total += $subtotal;
                $cartItems[] = [
                    'product' => $product,
                    'size' => $size,
                    'quantity' => $quantity,
                    'subtotal' => $subtotal
                ];
            }
        }

        $addresses = $userModel->getAddresses($_SESSION['user_id']);

        $data = [
            'title' => 'Checkout',
            'items' => $cartItems,
            'total' => $total,
            'addresses' => $addresses
        ];

        $this->view('templates/header', $data);
        $this->view('cart/checkout', $data);
        $this->view('templates/footer');
    }

    public function process() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (!isset($_SESSION['user_id'])) {
                header("Location: /php/Webdev/public/auth/login");
                exit;
            }

            if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
                header("Location: /php/Webdev/public/cart");
                exit;
            }

            // Validate required fields
            if (empty($_POST['address_id'])) {
                header("Location: /php/Webdev/public/cart/checkout?error=missing_address");
                exit;
            }

            $productModel = $this->model('ProductModel');
            $orderModel = $this->model('OrderModel');
            $userModel = $this->model('UserModel');

            // Handle Address
            $shipping_address = "";
            $addr = null;
            $user_addresses = $userModel->getAddresses($_SESSION['user_id']);
            foreach($user_addresses as $a) {
                if($a['id'] == $_POST['address_id']) {
                    $addr = $a;
                    break;
                }
            }
            if($addr) {
                $shipping_address = $addr['street_address'] . ", " . $addr['city'] . ", " . $addr['province'] . " " . $addr['postal_code'];
            } else {
                header("Location: /php/Webdev/public/cart/checkout?error=invalid_address");
                exit;
            }

            $total = 0;
            $items = [];
            
            foreach ($_SESSION['cart'] as $cartKey => $quantity) {
                $parts = explode('_', $cartKey);
                $id = $parts[0];
                $size = $parts[1] ?? 'Universal';

                $product = $productModel->getProductById($id);
                if ($product) {
                    $total += $product['price'] * $quantity;
                    $items[] = [
                        'product_id' => $id,
                        'size' => $size,
                        'quantity' => $quantity,
                        'price' => $product['price']
                    ];
                }
            }

            $orderData = [
                'user_id' => $_SESSION['user_id'],
                'total_price' => $total,
                'shipping_method' => $_POST['shipping_method'] ?? 'LBC',
                'payment_method' => $_POST['payment_method'] ?? 'COD',
                'shipping_address' => $shipping_address
            ];

            $order_id = $orderModel->createOrderFull($orderData);

            foreach ($items as $item) {
                $orderModel->addOrderItem($order_id, $item['product_id'], $item['size'], $item['quantity'], $item['price']);
                $productModel->subtractSizeStock($item['product_id'], $item['size'], $item['quantity']);
            }

            unset($_SESSION['cart']);

            header("Location: /php/Webdev/public/cart/success/" . $order_id);
            exit;
        }
        header("Location: /php/Webdev/public/cart");
        exit;
    }

    public function success($order_id = null) {
        if (!$order_id || !isset($_SESSION['user_id'])) {
            header("Location: /php/Webdev/public");
            exit;
        }

        $data = [
            'title' => 'Order Completed',
            'order_id' => $order_id
        ];

        $this->view('templates/header', $data);
        $this->view('cart/success', $data);
        $this->view('templates/footer');
    }
}
