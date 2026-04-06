<?php
require_once __DIR__ . "/../models/product/productModel.php";

class HomeService {

    private $productModel;

    public function __construct() {
        $this->productModel = new ProductModel();
    }

    public function getHomeData($page = 1, $limit = 8) {

        $offset = ($page - 1) * $limit;

        // Lấy danh sách sản phẩm
        $products = $this->productModel->getProducts($limit, $offset);

        // Tổng số sản phẩm
        $total = $this->productModel->countProducts();

        // Tổng số trang
        $totalPages = ceil($total / $limit);

        return [
            "products" => $products,
            "currentPage" => $page,
            "totalPages" => $totalPages
        ];
    }
}
?>