<?php

namespace App\Enums;

enum ReservationRoute: int
{
    // 　1：電話予約（自）　⇒　スタッフ入力で予約登録かつ、代理店コードを使用していない場合。
    // 　2：電話予約（代）　⇒　スタッフ入力で予約登録かつ、代理店コードを使用している場合。
    // 　3：当日受付予約　⇒　ユーザー手入力を使用して予約登録した場合。
    // 　4：ネット予約（自）　⇒　ユーザーが予約フォームを使用して予約登録かつ、代理店コードを使用していない場合。
    // 　5：ネット予約（代）　⇒　代理店ページから予約登録した場合またはユーザーが予約フォームを使用して予約登録かつ、代理店コードを使用している場合。

    // 基本情報
    case SELF_PHONE = 1;
    case STAFF_PHONE = 2;
    case COUNTER = 3;
    case SELF_WEB = 4;
    case STAFF_WEB = 5;

    // 日本語を追加
    public function label(): string
    {
        return match($this)
        {
            ReservationRoute::SELF_PHONE => '電話予約（自）',
            ReservationRoute::STAFF_PHONE => '電話予約（代）',
            ReservationRoute::COUNTER => '当日受付予約',
            ReservationRoute::SELF_WEB => 'ネット予約（自）',
            ReservationRoute::STAFF_WEB => 'ネット予約（代）',
        };
    }
}
