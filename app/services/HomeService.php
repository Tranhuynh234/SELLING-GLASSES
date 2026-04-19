<?php
require_once __DIR__ . "/../models/product/productModel.php";
require_once __DIR__ . "/../models/ReviewModel.php";
require_once __DIR__ . "/../models/product/comboModel.php";
require_once __DIR__ . "/../models/promotion/PromotionModel.php";
require_once __DIR__ . "/../models/userModel.php";

class HomeService {

    private $productModel;
    private $reviewModel;
    private $comboModel;
    private $promotionModel;
    private $userModel;
    private $conn;

    public function __construct() {
        $this->productModel = new ProductModel();
        $this->reviewModel = new ReviewModel();
        $this->comboModel = new ComboModel();
        $this->conn = Database::connect();
        $this->promotionModel = new PromotionModel($this->conn);
        $this->userModel = new UserModel();
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

    public function getDashboardStats() {
        try {
            // 1. Tổng số sản phẩm (từ bảng product - số lượng sản phẩm thực tế)
            $stmt = $this->conn->prepare("SELECT COUNT(*) as total FROM product");
            $stmt->execute();
            $productCount = $stmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;

            // 2. Tổng số mã giảm giá (promotion)
            $stmt = $this->conn->prepare("SELECT COUNT(*) as total FROM promotion");
            $stmt->execute();
            $promoCount = $stmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;

            // 3. Tổng số chính sách 
            $policyCount = 3;

            // 4. Tổng số khách hàng (users với role = 'customer')
            $stmt = $this->conn->prepare("SELECT COUNT(*) as total FROM users WHERE role = 'customer'");
            $stmt->execute();
            $customerCount = $stmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;

            return [
                'success' => true,
                'data' => [
                    'productCount' => (int)$productCount,
                    'promoCount' => (int)$promoCount,
                    'policyCount' => (int)$policyCount,
                    'customerCount' => (int)$customerCount
                ]
            ];
        } catch (Exception $e) {
            error_log("Error getting dashboard stats: " . $e->getMessage());
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    public function getOrderRevenueStats($period = 'daily') {
        try {
            if ($period === 'daily') {
                // Lấy doanh thu 7 ngày gần nhất
                $sql = "SELECT 
                            DATE(orderDate) as date,
                            COUNT(*) as orderCount,
                            COALESCE(SUM(totalPrice), 0) as revenue
                        FROM orders
                        WHERE orderDate >= DATE_SUB(NOW(), INTERVAL 7 DAY)
                        GROUP BY DATE(orderDate)
                        ORDER BY date ASC";
            } else {
                // Lấy doanh thu 12 tháng gần nhất
                $sql = "SELECT 
                            DATE_FORMAT(orderDate, '%Y-%m-01') as month,
                            COUNT(*) as orderCount,
                            COALESCE(SUM(totalPrice), 0) as revenue
                        FROM orders
                        WHERE orderDate >= DATE_SUB(NOW(), INTERVAL 12 MONTH)
                        GROUP BY DATE_FORMAT(orderDate, '%Y-%m')
                        ORDER BY month ASC";
            }

            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Format dữ liệu cho Chart.js
            $labels = [];
            $revenues = [];
            $orderCounts = [];

            foreach ($results as $row) {
                if ($period === 'daily') {
                    $labels[] = $row['date'];
                } else {
                    // Format tháng từ YYYY-MM-01
                    $monthStr = explode('-', $row['month']);
                    $labels[] = $monthStr[1] . '/' . $monthStr[0];
                }
                $revenues[] = (float)$row['revenue'];
                $orderCounts[] = (int)$row['orderCount'];
            }

            return [
                'success' => true,
                'data' => [
                    'labels' => $labels,
                    'revenues' => $revenues,
                    'orderCounts' => $orderCounts,
                    'period' => $period
                ]
            ];
        } catch (Exception $e) {
            error_log("Error getting order revenue stats: " . $e->getMessage());
            return [
                'success' => false,
                'message' => $e->getMessage(),
                'data' => [
                    'labels' => [],
                    'revenues' => [],
                    'orderCounts' => [],
                    'period' => $period
                ]
            ];
        }
    }
}
?>