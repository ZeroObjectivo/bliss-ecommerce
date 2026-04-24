<?php

class Home extends Controller {
    public function index() {
        $productModel = $this->model('ProductModel');
        $settingsModel = $this->model('SettingsModel');

        $featured = $productModel->getFeaturedProduct();
        $fallback = $settingsModel->getActiveFallback();
        
        // Ensure links have the correct base path
        if ($fallback) {
            $basePath = '/php/Webdev/public';
            if (isset($fallback['btn1_link']) && strpos($fallback['btn1_link'], '/') === 0 && strpos($fallback['btn1_link'], $basePath) !== 0) {
                $fallback['btn1_link'] = $basePath . $fallback['btn1_link'];
            }
            if (isset($fallback['btn2_link']) && strpos($fallback['btn2_link'], '/') === 0 && strpos($fallback['btn2_link'], $basePath) !== 0) {
                $fallback['btn2_link'] = $basePath . $fallback['btn2_link'];
            }
        }
        
        // Strictly get marked Featured products
        $featuredProducts = $productModel->getFeaturedProducts(3);
        
        $newArrivals = $productModel->getNewArrivals(3);
        $bestSellers = $productModel->getBestSellers(3);

        $data = [
            'title' => 'Home',
            'featured' => $featured,
            'fallback' => $fallback,
            'products' => $featuredProducts,
            'newArrivals' => $newArrivals,
            'bestSellers' => $bestSellers
        ];

        $this->view('templates/header', $data);
        $this->view('home/index', $data);
        $this->view('templates/footer');
    }
}
