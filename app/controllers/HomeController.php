<?php
require_once __DIR__ . "/../services/HomeService.php";

class HomeController {

    private $homeService;

    public function __construct() {
        $this->homeService = new HomeService();
    }

    public function index() {
        // Lấy số trang hiện tại từ URL, mặc định là trang 1
        $page = $_GET['page'] ?? 1;

        // Lấy toàn bộ dữ liệu từ HomeService
        $data = $this->homeService->getHomeData($page);

        // Trích xuất dữ liệu để trang home.php sử dụng 
        $products = $data['products'] ?? [];
        
        // Lấy danh sách đánh giá từ khách hàng
        $reviews = $this->homeService->getLatestReviews(5);

        // Nhúng file giao diện trang chủ
        require_once __DIR__ . "/../views/home/home.php";
    }
}
?>