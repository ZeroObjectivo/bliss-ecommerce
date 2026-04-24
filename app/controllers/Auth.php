<?php
class Auth extends Controller {
    public function login() {
        $data = ['title' => 'Sign In'];
        $this->view('templates/header', $data);
        $this->view('auth/login');
        $this->view('templates/footer');
    }

    public function register() {
        $data = ['title' => 'Create Account'];
        $this->view('templates/header', $data);
        $this->view('auth/register');
        $this->view('templates/footer');
    }

    public function process_login() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $userModel = $this->model('User');
            $user = $userModel->findUserByEmail($_POST['email']);

            if ($user && password_verify($_POST['password'], $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['name'];
                $_SESSION['user_role'] = $user['role'];
                $_SESSION['user_picture'] = $user['profile_picture'];
                
                // Load favorites into session cache
                $favoriteModel = $this->model('FavoriteModel');
                $_SESSION['favorites_list'] = $favoriteModel->getUserFavoriteIds($user['id']);

                // All users (including admins) get redirected to the homepage
                header("Location: /php/Webdev/public/");
                exit;
            } else {
                // Return to login with error (simulated via redirect for now)
                header("Location: /php/Webdev/public/auth/login?error=invalid");
                exit;
            }
        }
    }

    public function logout() {
        session_destroy();
        header("Location: /php/Webdev/public/auth/login");
        exit;
    }

    public function process_register() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $userModel = $this->model('User');
            $userModel->register([
                'name' => $_POST['name'],
                'email' => $_POST['email'],
                'password' => password_hash($_POST['password'], PASSWORD_DEFAULT)
            ]);
            header("Location: /php/Webdev/public/auth/login");
            exit;
        }
    }

    public function reset($token = null) {
        if (!$token) {
            header("Location: /php/Webdev/public/auth/login");
            exit;
        }

        $userModel = $this->model('UserModel');
        $user = $userModel->getUserByToken($token);

        $data = ['title' => 'Reset Password', 'token' => $token];
        
        if (!$user) {
            $data['error'] = 'This password reset link is invalid or has expired.';
        }

        $this->view('templates/header', $data);
        $this->view('auth/reset', $data);
        $this->view('templates/footer');
    }

    public function process_reset() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['token'], $_POST['password'], $_POST['password_confirm'])) {
            $token = $_POST['token'];
            $userModel = $this->model('UserModel');
            $user = $userModel->getUserByToken($token);

            if (!$user) {
                header("Location: /php/Webdev/public/auth/login?error=invalid_token");
                exit;
            }

            if ($_POST['password'] !== $_POST['password_confirm']) {
                $data = ['title' => 'Reset Password', 'token' => $token, 'error' => 'Passwords do not match.'];
                $this->view('templates/header', $data);
                $this->view('auth/reset', $data);
                $this->view('templates/footer');
                return;
            }

            $hashed = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $userModel->resetUserPassword($user['id'], $hashed);

            header("Location: /php/Webdev/public/auth/login?reset=success");
            exit;
        }
    }
}
