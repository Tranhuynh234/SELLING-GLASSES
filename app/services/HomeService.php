<?php
require_once __DIR__ . "/../models/product/productModel.php";
require_once __DIR__ . "/../models/ReviewModel.php";

class HomeService {

    private $productModel;
    private $reviewModel;

    public function __construct() {
        $this->productModel = new ProductModel();
        $this->reviewModel = new ReviewModel();
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

    public function getLatestReviews($limit = 5) {
        return $this->reviewModel->getLatestForHomePage($limit);
    }
}
?>