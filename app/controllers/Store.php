<?php

class Store extends Controller {
    public function index() {
        $data = [
            'title' => 'Find a Store'
        ];

        $this->view('templates/header', $data);
        $this->view('store/index', $data);
        $this->view('templates/footer');
    }
}
