<?php
require_once __DIR__ . "/../models/product/productModel.php";
require_once __DIR__ . "/../models/ReviewModel.php";
require_once __DIR__ . "/../models/product/comboModel.php";

class HomeService {

    private $productModel;
    private $reviewModel;
    private $comboModel;

    public function __construct() {
        $this->productModel = new ProductModel();
        $this->reviewModel = new ReviewModel();
        $this->comboModel = new ComboModel();
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

    public function getCombos($limit = 3) {
        try {
            return $this->comboModel->getAll(true, $limit, 0);
        } catch (Exception $e) {
            error_log("Error getting combos: " . $e->getMessage());
            return [];
        }
    }
}
?>