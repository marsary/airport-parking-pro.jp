<?php

namespace App\Enums\Cms;

enum SocSalesType: int
{
    // sales_type
    // define('MY_SALES_TYPE_PARK',		    1);		// 駐車
    // define('MY_SALES_TYPE_WAX',			2);		// 洗車
    // define('MY_SALES_TYPE_TRAVEL',		3);		// 旅行用品
    // define('MY_SALES_TYPE_INS',			4);		// 保険
    // define('MY_SALES_TYPE_MONTH',		5);		// 月極
    // define('MY_SALES_TYPE_ACQUASSIMO',	6);		// アクアシモ
    // define('MY_SALES_TYPE_OTHER',		99);	// その他

    // 基本情報
    case MY_SALES_TYPE_PARK = 1;
    case MY_SALES_TYPE_WAX = 2;
    case MY_SALES_TYPE_TRAVEL = 3;
    case MY_SALES_TYPE_INS = 4;
    case MY_SALES_TYPE_MONTH = 5;
    case MY_SALES_TYPE_ACQUASSIMO = 6;
    case MY_SALES_TYPE_OTHER = 99;

    // 日本語を追加
    public function label(): string
    {
        return match($this)
        {
            SocSalesType::MY_SALES_TYPE_PARK => '駐車',
            SocSalesType::MY_SALES_TYPE_WAX => '洗車',
            SocSalesType::MY_SALES_TYPE_TRAVEL => '旅行用品',
            SocSalesType::MY_SALES_TYPE_INS => '保険',
            SocSalesType::MY_SALES_TYPE_MONTH => '月極',
            SocSalesType::MY_SALES_TYPE_ACQUASSIMO => 'アクアシモ',
            SocSalesType::MY_SALES_TYPE_OTHER => 'その他',
        };
    }
}
