<?php

namespace App\Imports;

use App\Models\GoodCategory;
use App\Models\MonthlySalesTarget;
use App\Models\Office;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Row;


class MonthlySalesTargetImport implements OnEachRow, WithHeadingRow, WithChunkReading, WithValidation
{
    use Importable;

    /** @var Collection|null */
    private $offices = null;
    /** @var Collection|null */
    private $goodCategories = null;

    public function __construct()
    {
        // 事前に事業所と商品カテゴリをすべて取得しておくことで、ループ内でのDBアクセスを削減
        $this->offices = Office::pluck('id', 'name');
        $this->goodCategories = GoodCategory::pluck('id', 'name');
    }

    public function prepareForValidation($data, $index)
    {
        return $data;
    }


    public function onRow(Row $row)
    {
        // $rowIndex = $row->getIndex();
        $row = $row->toArray();

        dd($row);

        $officeId = $this->offices->get($row['事業所名']);
        $targetMonth = $row['対象年月'];

        // 各項目をループで処理
        $targets = [
            ['order' => MonthlySalesTarget::TOTAL_SALES_ORDER, 'sales_target' => $row['総売上目標'], 'good_category_name' => null],
            ['order' => MonthlySalesTarget::PARKING_FEE, 'sales_target' => $row['駐車料金売上目標'], 'good_category_name' => null],
            ['order' => 3, 'sales_target' => $row['商品カテゴリー1売上目標'], 'good_category_name' => $row['商品カテゴリー1名称']],
            ['order' => 4, 'sales_target' => $row['商品カテゴリー2売上目標'], 'good_category_name' => $row['商品カテゴリー2名称']],
            ['order' => 5, 'sales_target' => $row['商品カテゴリー3売上目標'], 'good_category_name' => $row['商品カテゴリー3名称']],
            ['order' => 6, 'sales_target' => $row['商品カテゴリー4売上目標'], 'good_category_name' => $row['商品カテゴリー4名称']],
        ];

        foreach ($targets as $target) {
            // 売上目標が設定されていない商品カテゴリはスキップ
            if (empty($target['sales_target']) && $target['order'] > 2) {
                continue;
            }

            $goodCategoryId = null;
            if ($target['good_category_name']) {
                $goodCategoryId = $this->goodCategories->get($target['good_category_name']);
                // CSVに記載のカテゴリ名がマスタに存在しない場合はスキップ
                if (!$goodCategoryId) {
                    continue;
                }
            }

            // 論理削除済みも含めて検索
            $monthlySalesTarget = MonthlySalesTarget::withTrashed()
                ->where('office_id', $officeId)
                ->where('target_month', $targetMonth)
                ->where('order', $target['order'])
                ->first();

            $dataToUpdate = [
                'good_category_id' => $goodCategoryId,
                'sales_target' => $target['sales_target'],
            ];

            if ($monthlySalesTarget) {
                // レコードが存在する場合
                if ($monthlySalesTarget->trashed()) {
                    // 論理削除されていたら復元
                    $monthlySalesTarget->restore();
                }
                // データを更新
                $monthlySalesTarget->update($dataToUpdate);
            } else {
                // レコードが存在しない場合は新規作成
                MonthlySalesTarget::create(array_merge(
                    [
                        'office_id' => $officeId,
                        'target_month' => $targetMonth,
                        'order' => $target['order'],
                    ],
                    $dataToUpdate
                ));
            }
        }
    }



    public function rules(): array
    {
        return [
            '*.事業所名' => 'required|exists:offices,name',
            '*.対象年月' => 'required|digits:6',
            '*.総売上目標' => 'required|int',
            '*.駐車料金売上目標' => 'required|int',
            '*.商品カテゴリー1名称' => 'nullable',
            '*.商品カテゴリー1売上目標' => 'nullable|int|required_with:*.商品カテゴリー1名称',
            '*.商品カテゴリー2名称' => 'nullable',
            '*.商品カテゴリー2売上目標' => 'nullable|int|required_with:*.商品カテゴリー2名称',
            '*.商品カテゴリー3名称' => 'nullable',
            '*.商品カテゴリー3売上目標' => 'nullable|int|required_with:*.商品カテゴリー3名称',
            '*.商品カテゴリー4名称' => 'nullable',
            '*.商品カテゴリー4売上目標' => 'nullable|int|required_with:*.商品カテゴリー4名称',
        ];
    }


    public function chunkSize(): int
    {
        return 100;
    }
}
