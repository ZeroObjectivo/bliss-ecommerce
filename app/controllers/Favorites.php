<?php
class Favorites extends Controller {
    public function index() {
        if (!isset($_SESSION['user_id'])) {
            header("Location: /php/Webdev/public/auth/login");
            exit;
        }

        $favoriteModel = $this->model('FavoriteModel');
        $favorites = $favoriteModel->getUserFavorites($_SESSION['user_id']);

        $data = [
            'title' => 'Your Favorites',
            'items' => $favorites
        ];

        $this->view('templates/header', $data);
        $this->view('favorites/index', $data);
        $this->view('templates/footer');
    }

    public function toggle() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['product_id'])) {
            if (!isset($_SESSION['user_id'])) {
                header("Location: /php/Webdev/public/auth/login");
                exit;
            }

            $productId = $_POST['product_id'];
            $userId = $_SESSION['user_id'];
            $favoriteModel = $this->model('FavoriteModel');

            if (!isset($_SESSION['favorites_list'])) {
                $_SESSION['favorites_list'] = [];
            }

            if (in_array($productId, $_SESSION['favorites_list'])) {
                // Remove
                $favoriteModel->removeFavorite($userId, $productId);
                $_SESSION['favorites_list'] = array_diff($_SESSION['favorites_list'], [$productId]);
            } else {
                // Add
                $favoriteModel->addFavorite($userId, $productId);
                $_SESSION['favorites_list'][] = (int)$productId;
            }

            // Redirect back or to favorites page
            if (isset($_SERVER['HTTP_REFERER'])) {
                header("Location: " . $_SERVER['HTTP_REFERER']);
            } else {
                header("Location: /php/Webdev/public/favorites");
            }
            exit;
        }
        header("Location: /php/Webdev/public/");
        exit;
    }

    public function clear() {
        if (!isset($_SESSION['user_id'])) {
            header("Location: /php/Webdev/public/auth/login");
            exit;
        }

        $favoriteModel = $this->model('FavoriteModel');
        $favoriteModel->clearUserFavorites($_SESSION['user_id']);
        $_SESSION['favorites_list'] = [];

        header("Location: /php/Webdev/public/favorites");
        exit;
    }
}
