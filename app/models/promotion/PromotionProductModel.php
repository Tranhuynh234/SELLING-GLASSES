<?php
class PromotionProductModel extends BaseModel {
    protected $table = 'promotion_product';

    public function applyPromotion($promotionId, $productIds) {
        // 1. Sử dụng hàm của BaseModel để bắt đầu Transaction
        $this->beginTransaction();

        try {
            // 2. Sử dụng queryOne (đã có prepare/execute bên trong BaseModel)
            $promotion = $this->queryOne("SELECT * FROM promotion WHERE promotionId = :id", [':id' => $promotionId]);
            
            if (!$promotion) {
                throw new Exception("Không tìm thấy chương trình khuyến mãi.");
            }

            foreach ($productIds as $productId) {
                // 3. Xóa khuyến mãi cũ bằng prepare/execute trực tiếp qua conn
                $stmtDel = $this->conn->prepare("DELETE FROM promotion_product WHERE productId = :pid");
                $stmtDel->execute([':pid' => $productId]);
                
                // 4. Sử dụng hàm create của BaseModel
                $this->create([
                    'promotionId' => $promotionId,
                    'productId'   => $productId
                ]);

                // 5. Lấy thông tin sản phẩm bằng queryOne
                $product = $this->queryOne("SELECT price, original_price FROM product WHERE productId = :pid", [':pid' => $productId]);
                
                if ($product) {
                    $this->updateProductPrice('product', 'productId', $productId, $product, $promotion);
                    
                    // 6. Lấy biến thể bằng queryAll
                    $variants = $this->queryAll("SELECT variantId, price, original_price FROM product_variant WHERE productId = :pid", [':pid' => $productId]);
                    
                    foreach ($variants as $variant) {
                        $this->updateProductPrice('product_variant', 'variantId', $variant['variantId'], $variant, $promotion);
                    }
                }
            }

            // 7. Xác nhận giao dịch
            $this->commit();
            return ["success" => true, "message" => "Áp dụng khuyến mãi thành công!"];

        } catch (Exception $e) {
            // 8. Rollback nếu lỗi
            $this->rollBack();
            error_log("Lỗi áp dụng KM: " . $e->getMessage());
            return ["success" => false, "message" => "Có lỗi xảy ra: " . $e->getMessage()];
        }
    }

    private function updateProductPrice($tableName, $idColumn, $idValue, $data, $promotion) {
        $originalPrice = $data['original_price'];
        $currentPrice = $data['price'];

        // Lưu giá gốc nếu chưa có
        if (is_null($originalPrice)) {
            $originalPrice = $currentPrice;
            $stmt = $this->conn->prepare("UPDATE $tableName SET original_price = :op WHERE $idColumn = :id");
            $stmt->execute([':op' => $originalPrice, ':id' => $idValue]);
        }

        // Tính giá mới
        $newPrice = ($promotion['discountType'] === 'percent') 
            ? $originalPrice - ($originalPrice * ($promotion['discount'] / 100))
            : $originalPrice - $promotion['discount'];

        $newPrice = max(0, $newPrice);

        // Cập nhật giá hiện tại
        $stmt = $this->conn->prepare("UPDATE $tableName SET price = :np WHERE $idColumn = :id");
        $stmt->execute([':np' => $newPrice, ':id' => $idValue]);
    }
    public function removePromotion($productIds) {
    $this->beginTransaction();

    try {
        foreach ($productIds as $productId) {
            // 1. Lấy giá gốc từ bảng product
            $product = $this->queryOne("SELECT original_price FROM product WHERE productId = :pid", [':pid' => $productId]);

            if ($product && !is_null($product['original_price'])) {
                // 2. Khôi phục price về original_price cho sản phẩm chính
                $stmt = $this->conn->prepare("UPDATE product SET price = original_price WHERE productId = :pid");
                $stmt->execute([':pid' => $productId]);

                // 3. Khôi phục tương tự cho các biến thể (variants)
                $stmtVar = $this->conn->prepare("UPDATE product_variant SET price = original_price WHERE productId = :pid");
                $stmtVar->execute([':pid' => $productId]);
                
                // 4. Xóa bản ghi trong bảng trung gian promotion_product
                $stmtDel = $this->conn->prepare("DELETE FROM promotion_product WHERE productId = :pid");
                $stmtDel->execute([':pid' => $productId]);
            }
        }

        $this->commit();
        return ["success" => true, "message" => "Đã hủy khuyến mãi và khôi phục giá gốc thành công!"];
    } catch (Exception $e) {
        $this->rollBack();
        error_log("Lỗi hủy KM: " . $e->getMessage());
        return ["success" => false, "message" => "Có lỗi khi hủy: " . $e->getMessage()];
    }
}
}