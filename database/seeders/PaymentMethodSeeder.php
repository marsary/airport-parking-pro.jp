<?php

namespace Database\Seeders;

use App\Models\PaymentMethod;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PaymentMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PaymentMethod::insert([
            // 1：現金
            [
                'id' => 1,
                'office_id' => 1,
                'name' => '現金',
                'type' => 1,
                'memo' => null,
                'multiple' => false,
            ],
            // 2：クレジット
            [
                'id' => 2,
                'office_id' => 1,
                'name' => 'クレジット1',
                'type' => 2,
                'memo' => null,
                'multiple' => true,
            ],
            // ３：電子マネー
            [
                'id' => 3,
                'office_id' => 1,
                'name' => '電子マネー1',
                'type' => 3,
                'memo' => null,
                'multiple' => true,
            ],
            // ４：QRコード
            [
                'id' => 4,
                'office_id' => 1,
                'name' => 'QRコード1',
                'type' => 4,
                'memo' => null,
                'multiple' => true,
            ],
            // ５：商品券
            [
                'id' => 5,
                'office_id' => 1,
                'name' => '商品券',
                'type' => 5,
                'memo' => null,
                'multiple' => false,
            ],
            // ６：旅行支援
            [
                'id' => 6,
                'office_id' => 1,
                'name' => '旅行支援1',
                'type' => 6,
                'memo' => null,
                'multiple' => true,
            ],
            // ７：バウチャ
            [
                'id' => 7,
                'office_id' => 1,
                'name' => 'バウチャ1',
                'type' => 7,
                'memo' => null,
                'multiple' => true,
            ],
            // ８：その他
            [
                'id' => 8,
                'office_id' => 1,
                'name' => 'その他1',
                'type' => 8,
                'memo' => null,
                'multiple' => true,
            ],
            // 2：クレジット
            [
                'id' => 9,
                'office_id' => 1,
                'name' => 'クレジット2',
                'type' => 2,
                'memo' => null,
                'multiple' => true,
            ],
            // ３：電子マネー
            [
                'id' => 10,
                'office_id' => 1,
                'name' => '電子マネー2',
                'type' => 3,
                'memo' => null,
                'multiple' => true,
            ],
            // ４：QRコード
            [
                'id' => 11,
                'office_id' => 1,
                'name' => 'QRコード2',
                'type' => 4,
                'memo' => null,
                'multiple' => true,
            ],
            // ６：旅行支援
            [
                'id' => 12,
                'office_id' => 1,
                'name' => '旅行支援2',
                'type' => 6,
                'memo' => null,
                'multiple' => true,
            ],
            // ７：バウチャ
            [
                'id' => 13,
                'office_id' => 1,
                'name' => 'バウチャ2',
                'type' => 7,
                'memo' => null,
                'multiple' => true,
            ],
            // ８：その他
            [
                'id' => 14,
                'office_id' => 1,
                'name' => 'その他2',
                'type' => 8,
                'memo' => null,
                'multiple' => true,
            ],
        ]);
    }
}
