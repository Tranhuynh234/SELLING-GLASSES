<?php
require_once __DIR__ . "/../services/HomeService.php";
class HomeController {

    private $homeService;

    public function __construct() {
        $this->homeService = new HomeService();
    }

    public function index() {

        $page = $_GET['page'] ?? 1;

        $data = $this->homeService->getHomeData($page);

        require_once __DIR__ . "/../views/home/home.php";
    }
}
?>