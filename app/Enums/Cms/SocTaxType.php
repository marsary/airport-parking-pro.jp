<?php

namespace App\Enums\Cms;

enum SocTaxType: int
{


    // 基本情報
    case MY_TAX_TYPE_NON = 0;
    case MY_TAX_TYPE_OUT = 1;
    case MY_TAX_TYPE_IN = 2;


    // 日本語を追加
    public function label(): string
    {
        return match($this)
        {
            SocTaxType::MY_TAX_TYPE_NON => '対象外',
            SocTaxType::MY_TAX_TYPE_OUT => '外税',
            SocTaxType::MY_TAX_TYPE_IN => '内税',
        };
    }
}
