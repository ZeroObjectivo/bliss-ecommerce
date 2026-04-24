<?php
class Catalog extends Controller {
    public function index() {
        $productModel = $this->model('ProductModel');
        
        $title = 'Catalog';
        if (isset($_GET['filter']) && $_GET['filter'] == 'featured') {
            $products = $productModel->getFeaturedProducts();
            $title = 'Featured Drops';
        } elseif (isset($_GET['filter']) && $_GET['filter'] == 'new') {
            $products = $productModel->getNewArrivals();
            $title = 'New Arrivals';
        } elseif (isset($_GET['filter']) && $_GET['filter'] == 'best') {
            $products = $productModel->getBestSellers();
            $title = 'Best Sellers';
        } elseif (isset($_GET['category']) && !empty($_GET['category'])) {
            $products = $productModel->getProductsByCategory($_GET['category']);
            $title = ucfirst($_GET['category']);
        } else {
            $products = $productModel->getAllProducts();
        }

        $data = [
            'title' => $title,
            'products' => $products
        ];

        $this->view('templates/header', $data);
        $this->view('catalog/index', $data);
        $this->view('templates/footer');
    }

    public function search_ajax() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' || isset($_GET['q'])) {
            $keyword = isset($_POST['q']) ? $_POST['q'] : $_GET['q'];
            
            $productModel = $this->model('ProductModel');
            $products = $productModel->searchProducts($keyword);
            
            header('Content-Type: application/json');
            echo json_encode(['results' => $products]);
            exit;
        }
    }
}
