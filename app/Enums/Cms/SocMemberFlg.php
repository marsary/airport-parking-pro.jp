<?php

namespace App\Enums\Cms;

enum SocMemberFlg: int
{
    // member_flg（予約サーバ用）
    // define('MY_USER_FLG_NOT_MEM',		0);		// 非メンバ（メンバ ID なしまたは未確認）。
    // define('MY_USER_FLG_MEM_FIXED',		1);		// メンバ ID 確定（出庫歴なし）。
    // define('MY_USER_FLG_TMP_MEM',		10);	// 代理店直接予約。
    // define('MY_USER_FLG_SUN_MEM',		20);	// メンバーカード発行済（出庫歴あり）。出庫のタイミングでこれになる。。か？

    // 基本情報
    case MY_USER_FLG_NOT_MEM = 0;
    case MY_USER_FLG_MEM_FIXED = 1;
    case MY_USER_FLG_TMP_MEM = 10;
    case MY_USER_FLG_SUN_MEM = 20;


    // 日本語を追加
    public function label(): string
    {
        return match($this)
        {
            SocMemberFlg::MY_USER_FLG_NOT_MEM => '非メンバ',
            SocMemberFlg::MY_USER_FLG_MEM_FIXED => 'メンバ ID 確定',
            SocMemberFlg::MY_USER_FLG_TMP_MEM => '代理店直接予約',
            SocMemberFlg::MY_USER_FLG_SUN_MEM => 'メンバーカード発行済',
        };
    }
}
