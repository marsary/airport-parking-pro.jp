<?php

namespace App\Enums\Graphs;

enum InventoryType: string
{
    // 税種別	tax_type	tinyInt	Yes		1：8％、2：10％、3：対象外

    // 基本情報
    case LOAD_COLUMN = "load_date";
    case UNLOAD_COLUMN = "unload_date_plan";
    case LOADTIME_COLUMN = "load_time";
    case UNLOADTIME_COLUMN = "unload_time_plan";
    case MAX_STOCK = "MAX在庫";
    case ENDING_STOCK = "おわり在庫";

    // 日本語を追加
    public function label(): string
    {
        return match($this)
        {
            InventoryType::LOAD_COLUMN => '入庫',
            InventoryType::UNLOAD_COLUMN => '出庫',
            InventoryType::MAX_STOCK => 'MAX在庫',
            InventoryType::ENDING_STOCK => 'おわり在庫',
        };
    }
}
