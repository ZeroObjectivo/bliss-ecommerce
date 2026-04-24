<?php

class Help extends Controller {
    public function index() {
        $data = [
            'title' => 'Help Center'
        ];

        $this->view('templates/header', $data);
        $this->view('help/index', $data);
        $this->view('templates/footer');
    }

    public function contact() {
        $data = [
            'title' => 'Contact Us'
        ];

        $this->view('templates/header', $data);
        $this->view('help/contact', $data);
        $this->view('templates/footer');
    }

    public function shipping_returns() {
        $data = [
            'title' => 'Shipping & Returns'
        ];

        $this->view('templates/header', $data);
        $this->view('help/shipping_returns', $data);
        $this->view('templates/footer');
    }

    public function send_concern() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (!isset($_SESSION['user_id'])) {
                header('Location: /php/Webdev/public/auth/login');
                exit;
            }

            $messageModel = $this->model('MessageModel');
            
            $redirectMap = [
                'contact' => 'help/contact',
                'shipping_returns' => 'help/shipping_returns'
            ];
            
            $redirect = isset($_POST['redirect']) && isset($redirectMap[$_POST['redirect']]) ? $redirectMap[$_POST['redirect']] : 'help';
            
            $data = [
                'user_id' => $_SESSION['user_id'],
                'subject' => trim($_POST['subject']),
                'message' => trim($_POST['message'])
            ];

            $insertedId = $messageModel->createMessage($data);
            if ($insertedId) {
                if (isset($_POST['redirect']) && $_POST['redirect'] === 'profile/inbox' || (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest')) {
                    $newMessage = $messageModel->getMessageById($insertedId);
                    echo json_encode(['success' => true, 'message' => 'message_sent', 'ticket' => $newMessage]);
                    exit;
                }
                header('Location: /php/Webdev/public/' . $redirect . '?success=message_sent');
            } else {
                if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                    echo json_encode(['success' => false, 'message' => 'failed_to_send']);
                    exit;
                }
                header('Location: /php/Webdev/public/' . $redirect . '?error=failed_to_send');
            }
        } else {
            header('Location: /php/Webdev/public/help');
        }
    }
}
