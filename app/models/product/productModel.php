<?php
require_once __DIR__ . "/../../core/BaseModel.php";
require_once __DIR__ . "/../../entities/product/Product.php";

class ProductModel extends BaseModel {
    protected $table = "product";

    // Tìm theo tên sản phẩm
    public function findByName($name) {
        $data = $this->findBy("name", $name);
        return $data ? new Product($data) : null;
    }

    public function findProduct($id) {
        $data = $this->find($id, "productId");
        return $data ? new Product($data) : null;
    }

    public function getAllProducts() {
        $rows = $this->all();
        $products = [];
        foreach ($rows as $row) {
            $products[] = new Product($row);
        }
        return $products;
    }
    
   public function getProducts($limit, $offset) {
    $sql = "SELECT * FROM product LIMIT $limit OFFSET $offset";
    $rows = $this->queryAll($sql); // Lấy mảng dữ liệu thô
    $products = [];
    foreach ($rows as $row) {
        $products[] = new Product($row); // Chuyển từng mảng dữ liệu thành đối tượng Product
    }
    return $products;   
   }


   // --- CRUD SẢN PHẨM ---
public function addProduct($data) {
        return $this->create([
            'name'        => $data['name'],
            'description' => $data['description'],
            'categoryId'  => $data['categoryId'],
            'imagePath'   => $data['imagePath'] ?? null,
            'staffId'     => $data['staffId']
        ]);
    }

    // 2. Thêm biến thể (Bảng product_variant)
    public function createVariant($data) {
        $sql = "INSERT INTO product_variant (color, size, price, productId, stock) 
                VALUES (:color, :size, :price, :productId, :stock)";
        
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':color'     => $data['color'],
            ':size'      => $data['size'],
            ':price'     => $data['price'],
            ':productId' => $data['productId'],
            ':stock'     => $data['stock']
        ]);
    }

    // 3. Xóa sản phẩm hoàn toàn (Dùng Transaction để không bị lỗi dữ liệu mồ côi)
    public function deleteProductComplete($id) {
        try {
            $this->conn->beginTransaction(); 

            // Xóa biến thể trước
            $sqlV = "DELETE FROM product_variant WHERE productId = :id";
            $stmtV = $this->conn->prepare($sqlV);
            $stmtV->execute([':id' => $id]);

            // Xóa sản phẩm chính
            $this->delete($id, "productId"); 

            $this->conn->commit(); 
            return true;
        } catch (Exception $e) {
            $this->conn->rollBack(); 
            return false;
        }
    }

    // 4. Lấy dữ liệu kèm giá/kho và Tên danh mục để hiện lên bảng Manager
    public function getAllProductsWithVariants() {
        // Anh JOIN thêm bảng category để lấy categoryName hiển thị cho người dùng xem
        $sql = "SELECT p.*, v.price, v.stock, v.color, v.size, c.name as categoryName
                FROM product p 
                LEFT JOIN product_variant v ON p.productId = v.productId 
                LEFT JOIN category c ON p.categoryId = c.categoryId
                GROUP BY p.productId
                ORDER BY p.productId DESC"; 
        return $this->queryAll($sql);
    }

    public function deleteProduct($id) {
        return $this->deleteProductComplete($id);
    }

   public function countProducts() {
        $sql = "SELECT COUNT(*) AS total FROM product";
        $result = $this->queryOne($sql);
        return $result['total'];
    }
}
