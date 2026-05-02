<?php
class Profile extends Controller {
    public function __construct() {
        if (!isset($_SESSION['user_id'])) {
            header("Location: /php/Webdev/public/auth/login");
            exit;
        }
    }

    public function index() {
        $userModel = $this->model('UserModel');
        $user = $userModel->getUserById($_SESSION['user_id']);
        $addresses = $userModel->getAddresses($_SESSION['user_id']);

        $has_security = !empty($user['security_q1']) && !empty($user['security_q2']) && !empty($user['security_q3']);

        $data = [
            'title' => 'My Account',
            'user' => $user,
            'addresses' => $addresses,
            'has_security' => $has_security
        ];

        $this->view('templates/header', $data);
        $this->view('profile/account', $data);
        $this->view('templates/footer');
    }

    public function update_security() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $userModel = $this->model('UserModel');
            $data = [
                'security_q1' => $_POST['security_question_1'],
                'security_a1' => strtolower(trim($_POST['security_answer_1'])),
                'security_q2' => $_POST['security_question_2'],
                'security_a2' => strtolower(trim($_POST['security_answer_2'])),
                'security_q3' => $_POST['security_question_3'],
                'security_a3' => strtolower(trim($_POST['security_answer_3']))
            ];

            if ($userModel->updateSecurityQuestions($_SESSION['user_id'], $data)) {
                header("Location: /php/Webdev/public/profile?success=security_updated");
            } else {
                header("Location: /php/Webdev/public/profile?error=security_update_failed");
            }
            exit;
        }
    }

    public function update_info() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $userModel = $this->model('UserModel');
            $data = [
                'name' => trim($_POST['name']),
                'username' => trim($_POST['username'])
            ];

            if ($userModel->updateProfile($_SESSION['user_id'], $data)) {
                $_SESSION['user_name'] = $data['name'];
                header("Location: /php/Webdev/public/profile?success=profile_updated");
            } else {
                header("Location: /php/Webdev/public/profile?error=update_failed");
            }
            exit;
        }
    }

    public function update_avatar() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['profile_picture'])) {
            $file = $_FILES['profile_picture'];
            
            // Check file size (5MB = 5 * 1024 * 1024 bytes)
            $maxSize = 5 * 1024 * 1024;
            if ($file['size'] > $maxSize) {
                header("Location: /php/Webdev/public/profile?error=file_too_large");
                exit;
            }

            $target_dir = "uploads/profiles/";
            
            if (!is_dir($target_dir)) {
                mkdir($target_dir, 0777, true);
            }

            $file_ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
            $new_name = time() . "_" . $_SESSION['user_id'] . "." . $file_ext;
            $target_file = $target_dir . $new_name;

            if (move_uploaded_file($file['tmp_name'], $target_file)) {
                $userModel = $this->model('UserModel');
                $userModel->updateProfilePicture($_SESSION['user_id'], $target_file);
                $_SESSION['user_picture'] = $target_file;
                header("Location: /php/Webdev/public/profile?success=avatar_updated");
            } else {
                header("Location: /php/Webdev/public/profile?error=upload_failed");
            }
            exit;
        }
    }

    public function update_avatar_ajax() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['profile_picture'])) {
            $file = $_FILES['profile_picture'];
            
            // Check file size (5MB)
            $maxSize = 5 * 1024 * 1024;
            if ($file['size'] > $maxSize) {
                echo json_encode(['success' => false, 'error' => 'File size exceeds 5MB limit.']);
                exit;
            }

            $userModel = $this->model('UserModel');
            $user = $userModel->getUserById($_SESSION['user_id']);

            // Delete old picture if it exists
            if (!empty($user['profile_picture']) && file_exists($user['profile_picture'])) {
                unlink($user['profile_picture']);
            }

            $target_dir = "uploads/profiles/";
            if (!is_dir($target_dir)) {
                mkdir($target_dir, 0777, true);
            }

            // Always png since we generate it from canvas
            $new_name = time() . "_" . $_SESSION['user_id'] . ".png";
            $target_file = $target_dir . $new_name;

            if (move_uploaded_file($file['tmp_name'], $target_file)) {
                $userModel->updateProfilePicture($_SESSION['user_id'], $target_file);
                $_SESSION['user_picture'] = $target_file;
                
                echo json_encode([
                    'success' => true, 
                    'path' => $target_file,
                    'message' => 'Profile picture updated successfully.'
                ]);
            } else {
                echo json_encode(['success' => false, 'error' => 'Failed to save uploaded image.']);
            }
            exit;
        }
    }

    public function remove_avatar_ajax() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $userModel = $this->model('UserModel');
            $user = $userModel->getUserById($_SESSION['user_id']);

            if (!empty($user['profile_picture'])) {
                if (file_exists($user['profile_picture'])) {
                    unlink($user['profile_picture']);
                }
                
                if ($userModel->removeProfilePicture($_SESSION['user_id'])) {
                    $_SESSION['user_picture'] = null;
                    echo json_encode([
                        'success' => true,
                        'message' => 'Profile picture removed successfully.',
                        'initial' => strtoupper(substr($user['name'], 0, 1))
                    ]);
                } else {
                    echo json_encode(['success' => false, 'error' => 'Database update failed.']);
                }
            } else {
                echo json_encode(['success' => false, 'error' => 'No profile picture to remove.']);
            }
            exit;
        }
    }

    public function update_password() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $userModel = $this->model('UserModel');
            $user = $userModel->getUserById($_SESSION['user_id']);

            $current = $_POST['current_password'];
            $new = $_POST['new_password'];
            $confirm = $_POST['confirm_password'];

            if (password_verify($current, $user['password'])) {
                if ($new === $confirm) {
                    $val = $this->validatePassword($new);
                    if ($val !== true) {
                        header("Location: /php/Webdev/public/profile?error=weak_password&msg=" . urlencode($val));
                        exit;
                    }

                    $hashed = password_hash($new, PASSWORD_DEFAULT);
                    $userModel->resetUserPassword($_SESSION['user_id'], $hashed);
                    header("Location: /php/Webdev/public/profile?success=password_changed");
                } else {
                    header("Location: /php/Webdev/public/profile?error=password_mismatch");
                }
            } else {
                header("Location: /php/Webdev/public/profile?error=wrong_current_password");
            }
            exit;
        }
    }

    public function add_address() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
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
                header("Location: /php/Webdev/public/profile?success=address_added");
            } else {
                header("Location: /php/Webdev/public/profile?error=address_failed");
            }
            exit;
        }
    }

    public function edit_address($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $userModel = $this->model('UserModel');
            $data = [
                'id' => $id,
                'user_id' => $_SESSION['user_id'],
                'street_address' => trim($_POST['street_address']),
                'city' => trim($_POST['city']),
                'province' => trim($_POST['province']),
                'postal_code' => trim($_POST['postal_code']),
                'category' => trim($_POST['category'] ?? 'Home Address')
            ];

            if ($userModel->updateAddress($data)) {
                header("Location: /php/Webdev/public/profile?success=address_updated");
            } else {
                header("Location: /php/Webdev/public/profile?error=address_update_failed");
            }
            exit;
        }
    }

    public function delete_address($id) {
        $userModel = $this->model('UserModel');
        if ($userModel->deleteAddress($id, $_SESSION['user_id'])) {
            header("Location: /php/Webdev/public/profile?success=address_deleted");
        } else {
            header("Location: /php/Webdev/public/profile?error=delete_failed");
        }
        exit;
    }

    public function set_default_address($id) {
        $userModel = $this->model('UserModel');
        if ($userModel->setDefaultAddress($id, $_SESSION['user_id'])) {
            header("Location: /php/Webdev/public/profile?success=default_set");
        } else {
            header("Location: /php/Webdev/public/profile?error=default_failed");
        }
        exit;
    }

    public function orders() {
        $userModel = $this->model('UserModel');
        $user = $userModel->getUserById($_SESSION['user_id']);
        
        $orderModel = $this->model('OrderModel');
        $allOrders = $orderModel->getOrdersByUser($_SESSION['user_id']);

        $pending = array_values(array_filter($allOrders, fn($o) => $o['status'] == 'pending' || $o['status'] == 'processing'));
        $shipped = array_values(array_filter($allOrders, fn($o) => $o['status'] == 'shipped'));
        $delivered = array_values(array_filter($allOrders, fn($o) => $o['status'] == 'delivered'));
        $completed = array_values(array_filter($allOrders, fn($o) => $o['status'] == 'completed'));
        $cancelled = array_values(array_filter($allOrders, fn($o) => $o['status'] == 'cancelled'));

        $data = [
            'title' => 'My Orders',
            'user' => $user,
            'pending' => $pending,
            'shipped' => $shipped,
            'delivered' => $delivered,
            'completed' => $completed,
            'cancelled' => $cancelled
        ];

        $this->view('templates/header', $data);
        $this->view('profile/orders', $data);
        $this->view('templates/footer');
    }

    public function order_details($id) {
        $userModel = $this->model('UserModel');
        $user = $userModel->getUserById($_SESSION['user_id']);
        
        $orderModel = $this->model('OrderModel');
        $order = $orderModel->getOrderById($id);

        // Security: Ensure order belongs to user
        if (!$order || $order['user_id'] != $_SESSION['user_id']) {
            header("Location: /php/Webdev/public/profile/orders");
            exit;
        }

        $items = $orderModel->getOrderItems($id);

        $data = [
            'title' => 'Order Details #' . $id,
            'user' => $user,
            'order' => $order,
            'items' => $items
        ];

        $this->view('templates/header', $data);
        $this->view('profile/order_detail', $data);
        $this->view('templates/footer');
    }

    public function confirm_receipt($id) {
        $orderModel = $this->model('OrderModel');
        $order = $orderModel->getOrderById($id);

        if ($order && $order['user_id'] == $_SESSION['user_id'] && $order['status'] == 'delivered') {
            if ($orderModel->updateStatus($id, 'completed')) {
                // Already archived or archive here if not done by admin
                $orderModel->archiveOrder($id);
                header("Location: /php/Webdev/public/profile/order_details/" . $id . "?success=order_completed");
                exit;
            }
        }
        header("Location: /php/Webdev/public/profile/orders?error=confirmation_failed");
        exit;
    }

    public function inbox() {
        $userModel = $this->model('UserModel');
        $user = $userModel->getUserById($_SESSION['user_id']);

        $messageModel = $this->model('MessageModel');
        $messages = $messageModel->getMessagesByUserId($_SESSION['user_id'], true);

        // Attach replies to each message
        foreach ($messages as &$msg) {
            $msg['replies'] = $messageModel->getReplies($msg['id']);
        }

        $data = [
            'title' => 'My Inbox',
            'user' => $user,
            'messages' => $messages
        ];

        $this->view('templates/header', $data);
        $this->view('profile/inbox', $data);
        $this->view('templates/footer');
    }

    public function reply_ticket() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $messageModel = $this->model('MessageModel');
            $id = $_POST['id'];
            $reply = trim($_POST['reply']);
            $sender_id = $_SESSION['user_id'];

            if ($messageModel->addReply($id, $sender_id, $reply, 'active')) {
                $autoReply = $messageModel->addAutoReply($id);
                if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                    echo json_encode([
                        'success' => true, 
                        'message' => 'reply_sent', 
                        'reply_text' => $reply, 
                        'created_at' => date('Y-m-d H:i:s'),
                        'auto_reply' => $autoReply
                    ]);
                    exit;
                }
                header("Location: /php/Webdev/public/profile/inbox?success=reply_sent");
            } else {
                if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                    echo json_encode(['success' => false, 'message' => 'failed_to_reply']);
                    exit;
                }
                header("Location: /php/Webdev/public/profile/inbox?error=failed_to_reply");
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
        $messageModel->markAsReadUser($id, $_SESSION['user_id']);
        echo json_encode(['success' => true]);
        exit;
    }

    public function message_archive($id) {
        $messageModel = $this->model('MessageModel');
        if ($messageModel->archiveUser($id, $_SESSION['user_id'])) {
            header("Location: /php/Webdev/public/profile/inbox?success=message_archived");
        } else {
            header("Location: /php/Webdev/public/profile/inbox?error=archive_failed");
        }
    }

    public function message_unarchive($id) {
        $messageModel = $this->model('MessageModel');
        if ($messageModel->unarchiveUser($id, $_SESSION['user_id'])) {
            header("Location: /php/Webdev/public/profile/inbox?success=message_unarchived");
        } else {
            header("Location: /php/Webdev/public/profile/inbox?error=unarchive_failed");
        }
    }

    public function message_delete($id) {
        $messageModel = $this->model('MessageModel');
        if ($messageModel->deleteMessageUser($id, $_SESSION['user_id'])) {
            header("Location: /php/Webdev/public/profile/inbox?success=message_deleted_permanently");
        } else {
            header("Location: /php/Webdev/public/profile/inbox?error=delete_failed");
        }
    }
}
