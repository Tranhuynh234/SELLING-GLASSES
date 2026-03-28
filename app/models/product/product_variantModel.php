<?php
require_once __DIR__ . "/../../core/BaseModel.php";
require_once __DIR__ . "/../../entities/product/ProductVariant.php";

class ProductVariantModel extends BaseModel {
    protected $table = "product_variant";

    public function findVariant($id) {
        $data = $this->find($id, "variantId");
        return $data ? new ProductVariant($data) : null;
    }

    // Lấy danh sách màu/size cho 1 sản phẩm
    public function getVariantsByProductId($productId) {
        $sql = "SELECT * FROM {$this->table} WHERE productId = :productId";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':productId' => $productId]);
        $rows = $stmt->fetchAll();

        $variants = [];
        foreach ($rows as $row) {
            $variants[] = new ProductVariant($row);
        }
        return $variants;
    }
}
