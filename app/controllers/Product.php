<?php
class Product extends Controller {
    public function detail($id = null) {
        if (!$id) {
            header("Location: /php/Webdev/public/catalog");
            exit;
        }

        $productModel = $this->model('ProductModel');
        $product = $productModel->getProductById($id);

        if (!$product) {
            header("Location: /php/Webdev/public/catalog");
            exit;
        }

        $data = [
            'title' => $product['name'],
            'product' => $product
        ];

        $this->view('templates/header', $data);
        $this->view('product/view', $data);
        $this->view('templates/footer');
    }
}
