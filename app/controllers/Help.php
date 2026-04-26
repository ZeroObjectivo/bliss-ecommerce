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

    public function track_ticket() {
        $data = ['title' => 'Track Your Request'];
        $this->view('templates/header', $data);
        $this->view('help/track', $data);
        $this->view('templates/footer');
    }

    public function process_track_ticket() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $ticket = trim($_POST['ticket_number']);
            $email = trim($_POST['email']);

            $messageModel = $this->model('MessageModel');
            $message = $messageModel->getMessageByTicket($ticket, $email);

            if ($message) {
                // Redirect to detail view with a temporary session-based permission if needed
                // For simplicity, we'll use URL parameters for this non-sensitive guest lookup
                header("Location: /php/Webdev/public/help/view_ticket/$ticket?email=" . urlencode($email));
                exit;
            } else {
                header("Location: /php/Webdev/public/help/track_ticket?error=not_found");
                exit;
            }
        }
    }

    public function view_ticket($ticket_number) {
        $email = $_GET['email'] ?? '';
        $messageModel = $this->model('MessageModel');
        $message = $messageModel->getMessageByTicket($ticket_number, $email);

        if (!$message) {
            header("Location: /php/Webdev/public/help/track_ticket?error=access_denied");
            exit;
        }

        $data = [
            'title' => 'Ticket ' . $ticket_number,
            'message' => $message,
            'replies' => $messageModel->getReplies($message['id']),
            'ticket_number' => $ticket_number,
            'email' => $email
        ];

        $this->view('templates/header', $data);
        $this->view('help/view_ticket', $data);
        $this->view('templates/footer');
    }

    public function reply_guest() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $ticket = $_POST['ticket_number'];
            $email = $_POST['email'];
            $reply_text = trim($_POST['reply']);

            $messageModel = $this->model('MessageModel');
            $message = $messageModel->getMessageByTicket($ticket, $email);

            if ($message && !empty($reply_text)) {
                // sender_id is NULL for guest
                $messageModel->addReply($message['id'], null, $reply_text, 'active');
                header("Location: /php/Webdev/public/help/view_ticket/$ticket?email=" . urlencode($email) . "&success=replied");
            } else {
                header("Location: /php/Webdev/public/help/track_ticket?error=reply_failed");
            }
        }
    }

    public function send_concern() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $email = isset($_POST['email']) ? trim($_POST['email']) : null;
            $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

            if (!$user_id && !$email) {
                header('Location: /php/Webdev/public/auth/login?error=auth_required');
                exit;
            }

            $messageModel = $this->model('MessageModel');
            
            $redirectMap = [
                'contact' => 'help/contact',
                'shipping_returns' => 'help/shipping_returns'
            ];
            
            $redirect = isset($_POST['redirect']) && isset($redirectMap[$_POST['redirect']]) ? $redirectMap[$_POST['redirect']] : 'help';
            
            $data = [
                'user_id' => $user_id,
                'email' => $email,
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
