<?php
class AdminAuth extends Controller {
    public function login() {
        // Auto-bridge if coming from customer side
        if (!isset($_SESSION['admin_id']) && isset($_SESSION['user_id']) && isset($_SESSION['user_role'])) {
            if ($_SESSION['user_role'] === 'admin' || $_SESSION['user_role'] === 'superadmin') {
                $_SESSION['admin_id'] = $_SESSION['user_id'];
                $_SESSION['admin_name'] = $_SESSION['user_name'];
                $_SESSION['admin_role'] = $_SESSION['user_role'];

                // Update last login for admin bridge
                $userModelObj = $this->model('UserModel');
                $userModelObj->updateLastLogin($_SESSION['admin_id']);
            }
        }

        if (isset($_SESSION['admin_id'])) {
            header("Location: /php/Webdev/public/admin");
            exit;
        }
        $data = ['title' => 'Admin Login'];
        $this->view('admin/auth/login', $data);
    }

    public function process_login() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $userModel = $this->model('User');
            $user = $userModel->findUserByEmail($_POST['email']);

            if ($user && ($user['role'] === 'admin' || $user['role'] === 'superadmin') && password_verify($_POST['password'], $user['password'])) {
                // Check if admin is suspended
                if ($user['status'] === 'inactive') {
                    header("Location: /php/Webdev/public/adminauth/login?error=suspended");
                    exit;
                }

                $_SESSION['admin_id'] = $user['id'];
                $_SESSION['admin_name'] = $user['name'];
                $_SESSION['admin_role'] = $user['role'];
                
                // Update last login
                $userModelObj = $this->model('UserModel');
                $userModelObj->updateLastLogin($user['id']);

                header("Location: /php/Webdev/public/admin");
                exit;
            } else {
                header("Location: /php/Webdev/public/adminauth/login?error=invalid");
                exit;
            }
        }
    }

    public function logout() {
        // To properly logout from admin when auto-bridge is active, 
        // we must clear the entire session otherwise the auto-bridge 
        // in login() will immediately log the user back in.
        session_unset();
        session_destroy();
        header("Location: /php/Webdev/public/adminauth/login");
        exit;
    }
}
