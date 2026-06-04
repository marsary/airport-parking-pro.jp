<?php
namespace App\Services\Soc;

use App\Enums\Cms\SocMemberFlg;
use App\Models\Deal;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AfterSyncService
{
    public int $syncCount;
    public array $results = [];

    function __construct()
    {
        $this->syncCount = 0;
    }

    public function updateAfterSync(array $dealData)
    {
            error_log('updateAfterSync'."\n",3,"../storage/logs/test.log");
        foreach($dealData as $row) {
            try {
                $service = $this;
                DB::transaction(function () use ($row, $service) {

                    // リクエストデータのサニタイズ
                    $sanitized = $this->sanitize($row);

                    // データベースの更新処理
                    $result = $this->updateDb($sanitized);
                    $service->results[] = $result;

                });

            } catch (\Exception $e) {
                Log::error('エラー内容：' . $e->getMessage());
                $this->results[] = [
                    'rsv_id1' => $row['rsv_id1'],
                    'syncStatus' => $row,
                    'dealUpdated' => false,
                    'memberUpdated' => false,
                ];
            }

        }
    }

    private function updateDb(array $sanitized)
    {
        $deal = Deal::findOrFail($sanitized['id']);
            error_log('updateDb'."\n",3,"../storage/logs/test.log");

        $result =[
            'id' => $deal->id,
            'rsv_id1' => $sanitized['id'],
            'syncStatus' => $sanitized,
            'dealUpdated' => false,
            'memberUpdated' => false,
        ];

        if($sanitized['sync_flg']) {
            $deal->sync_flg = $sanitized['sync_flg'];
            $deal->synced_at = Carbon::now();
            $deal->save();
            $this->syncCount++;
            $result['dealUpdated'] = true;
        }

        if($sanitized['soc_member_id'] || $sanitized['soc_member_flg']) {
            error_log('soc_member_flg'."\n",3,"../storage/logs/test.log");
            error_log($sanitized['soc_member_flg']."\n",3,"../storage/logs/test.log");
            $member= $deal->member;
            error_log(json_encode($member)."\n",3,"../storage/logs/test.log");
            if($sanitized['soc_member_id']) {
                $member->soc_member_id = $sanitized['soc_member_id'];
            }
            if($sanitized['soc_member_flg']) {
                $member->soc_member_flg = $sanitized['soc_member_flg'];
            }
            $member->save();
            $result['memberUpdated'] = true;
        }
        return $result;
    }

    private function sanitize(array $row)
    {
        // 会員フラグ (member_flg)：
        // MY_USER_FLG_MEM_FIXED または MY_USER_FLG_SUN_MEM の場合のみ値を受け入れる
        // それ以外（例えば 0 に戻そうとする操作など）は認めず、一律 0 として処理
        $memberFlg = (int)$row['member_flg'];

        if (	$memberFlg != SocMemberFlg::MY_USER_FLG_MEM_FIXED->value
            &&	$memberFlg != SocMemberFlg::MY_USER_FLG_SUN_MEM->value
        ) {
            $memberFlg = 0;
        }

        return [
            'id' => (int)$row['rsv_id1'],
            'sync_flg' => (int)(bool)(int)$row['sync_flg'],
            'soc_member_id' => (int)$row['u_id'],
            'soc_member_flg' => $memberFlg,
        ];

    }
}
