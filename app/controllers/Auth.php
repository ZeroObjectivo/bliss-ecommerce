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
                // Check if user is suspended
                if ($user['status'] === 'inactive') {
                    header("Location: /php/Webdev/public/auth/login?error=suspended");
                    exit;
                }

                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['name'];
                $_SESSION['user_role'] = $user['role'];
                $_SESSION['user_picture'] = $user['profile_picture'];
                
                // Update last login
                $userModelObj = $this->model('UserModel');
                $userModelObj->updateLastLogin($user['id']);

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

    public function forgot_password() {
        $data = ['title' => 'Forgot Password'];
        $this->view('templates/header', $data);
        $this->view('auth/forgot_password', $data);
        $this->view('templates/footer');
    }

    public function process_forgot_password() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['email'])) {
            $userModel = $this->model('User');
            $user = $userModel->findUserByEmail($_POST['email']);

            if ($user) {
                $_SESSION['reset_email'] = $user['email'];
                header("Location: /php/Webdev/public/auth/verify_questions");
                exit;
            } else {
                header("Location: /php/Webdev/public/auth/forgot_password?error=not_found");
                exit;
            }
        }
    }

    public function verify_questions() {
        if (!isset($_SESSION['reset_email'])) {
            header("Location: /php/Webdev/public/auth/forgot_password");
            exit;
        }

        $userModel = $this->model('User');
        $user = $userModel->findUserByEmail($_SESSION['reset_email']);

        $data = [
            'title' => 'Security Verification',
            'q1' => $user['security_q1'],
            'q2' => $user['security_q2'],
            'q3' => $user['security_q3']
        ];

        $this->view('templates/header', $data);
        $this->view('auth/verify_questions', $data);
        $this->view('templates/footer');
    }

    public function process_verify_questions() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_SESSION['reset_email'])) {
            $userModel = $this->model('User');
            $user = $userModel->findUserByEmail($_SESSION['reset_email']);

            $a1 = strtolower(trim($_POST['a1']));
            $a2 = strtolower(trim($_POST['a2']));
            $a3 = strtolower(trim($_POST['a3']));

            if ($a1 === $user['security_a1'] && $a2 === $user['security_a2'] && $a3 === $user['security_a3']) {
                // Generate token
                $token = bin2hex(random_bytes(32));
                $userModelObj = $this->model('UserModel');
                $userModelObj->setResetToken($user['id'], $token);
                
                unset($_SESSION['reset_email']);
                header("Location: /php/Webdev/public/auth/reset/" . $token);
                exit;
            } else {
                header("Location: /php/Webdev/public/auth/verify_questions?error=incorrect");
                exit;
            }
        }
    }

    public function process_register() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $userModel = $this->model('User');
            $userModel->register([
                'name' => $_POST['name'],
                'email' => $_POST['email'],
                'password' => password_hash($_POST['password'], PASSWORD_DEFAULT),
                'security_q1' => $_POST['security_question_1'],
                'security_a1' => strtolower(trim($_POST['security_answer_1'])),
                'security_q2' => $_POST['security_question_2'],
                'security_a2' => strtolower(trim($_POST['security_answer_2'])),
                'security_q3' => $_POST['security_question_3'],
                'security_a3' => strtolower(trim($_POST['security_answer_3']))
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
