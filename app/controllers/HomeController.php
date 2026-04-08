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

        /** * TRÍCH XUẤT DỮ LIỆU ĐỂ TRANG HOME.PHP SỬ DỤNG
         * Giả sử HomeService trả về mảng có dạng ['products' => [...], 'pagination' => ...]
         * Ta gán mảng products vào biến $products để vòng lặp foreach trong view hoạt động.
         */
        $products = $data['products'] ?? []; 

        // Nhúng file giao diện trang chủ
        require_once __DIR__ . "/../views/home/home.php";
    }
}
?>