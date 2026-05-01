<?php

namespace App\Enums\Cms;

enum SocOfficeId: int
{
    // o_id
    // define('MY_MAIN_OFFICE_ID',				1);	// 成田店
    // define('MY_MAIN_OFFICE_ID_DATE',		4);	// 浮島店 国際		日付は国際が中心。
    // define('MY_OFFICE_ID_NARITA',			1);	// 成田店
    // define('MY_OFFICE_ID_RED',				2);	// レッド
    // define('MY_OFFICE_ID_UKISHIMA',			3);	// 浮島店 国内
    // define('MY_OFFICE_ID_OOI',				4);	// 大井店			(Obsolete)
    // define('MY_OFFICE_ID_UKISHIMA_I11L',	4);	// 浮島店 国際		I11L = International。造語。	reserve.js に値直書きあり。
    // define('MY_OFFICE_ID_PREMIER',			5);	// プレミア
    // define('MY_OFFICE_ID_IES',				6);	// アイエス

    /**
     * 基本情報
     */
    case MY_OFFICE_ID_NARITA = 1;
    case MY_OFFICE_ID_RED = 2;
    case MY_OFFICE_ID_UKISHIMA = 3;
    case MY_OFFICE_ID_UKISHIMA_INTL = 4;
    case MY_OFFICE_ID_PREMIER = 5;
    case MY_OFFICE_ID_IES = 6;

    // 日本語を追加
    public function label(): string
    {
        return match($this)
        {
            SocOfficeId::MY_OFFICE_ID_NARITA => '成田店',
            SocOfficeId::MY_OFFICE_ID_RED => 'レッド',
            SocOfficeId::MY_OFFICE_ID_UKISHIMA => '浮島店 国内',
            SocOfficeId::MY_OFFICE_ID_UKISHIMA_INTL => '浮島店 国際',
            SocOfficeId::MY_OFFICE_ID_PREMIER => 'プレミア',
            SocOfficeId::MY_OFFICE_ID_IES => 'アイエス',
        };
    }
}
