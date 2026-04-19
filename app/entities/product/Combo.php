<?php

/** Đại diện cho một combo sản phẩm */
class Combo
{
    public $comboId;
    public $name;
    public $description;
    public $imagePath;
    public $price;
    public $isActive;
    public $staffId;
    public $createdAt;
    public $updatedAt;
    public $deletedAt;
    
    // Danh sách sản phẩm trong combo
    public $items = []; 

    public function __construct(
        $name,
        $price,
        $description = null,
        $imagePath = null,
        $isActive = true,
        $staffId = null
    ) {
        $this->name = $name;
        $this->price = $price;
        $this->description = $description;
        $this->imagePath = $imagePath;
        $this->isActive = $isActive;
        $this->staffId = $staffId;
    }

    /** Thêm sản phẩm vào combo */
    public function addItem($productId, $quantity = 1)
    {
        $this->items[] = [
            'productId' => $productId,
            'quantity' => $quantity
        ];
    }

    /** Loại bỏ sản phẩm khỏi combo */
    public function removeItem($productId)
    {
        $this->items = array_filter($this->items, function ($item) use ($productId) {
            return $item['productId'] !== $productId;
        });
    }

    /** Xóa tất cả sản phẩm trong combo */
    public function clearItems()
    {
        $this->items = [];
    }

    /** Lấy tổng số sản phẩm trong combo */
    public function getItemCount()
    {
        return count($this->items);
    }

    /** Chuyển đổi entity thành array */
    public function toArray()
    {
        return [
            'comboId' => $this->comboId ?? null,
            'name' => $this->name,
            'description' => $this->description,
            'imagePath' => $this->imagePath,
            'price' => $this->price,
            'isActive' => $this->isActive,
            'staffId' => $this->staffId,
            'createdAt' => $this->createdAt,
            'updatedAt' => $this->updatedAt,
            'deletedAt' => $this->deletedAt,
            'items' => $this->items
        ];
    }

    /** Tạo entity từ array */
    public static function fromArray($data)
    {
        $combo = new self(
            $data['name'] ?? '',
            (float)($data['price'] ?? 0),
            $data['description'] ?? null,
            $data['imagePath'] ?? null,
            (bool)($data['isActive'] ?? true),
            isset($data['staffId']) ? (int)$data['staffId'] : null
        );

        if (isset($data['comboId'])) {
            $combo->comboId = (int)$data['comboId'];
        }
        if (isset($data['createdAt'])) {
            $combo->createdAt = $data['createdAt'];
        }
        if (isset($data['updatedAt'])) {
            $combo->updatedAt = $data['updatedAt'];
        }
        if (isset($data['deletedAt'])) {
            $combo->deletedAt = $data['deletedAt'];
        }
        if (isset($data['items']) && is_array($data['items'])) {
            $combo->items = $data['items'];
        }

        return $combo;
    }
}
