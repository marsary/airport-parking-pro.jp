<?php
namespace App\Services\Soc;

use App\Enums\Cms\SocMemberFlg;
use App\Enums\GeneralStatus;
use App\Models\Deal;
use App\Models\Member;
use App\Models\MemberType;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

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
            $member= $this->getOrNewMember($deal);
            if($sanitized['soc_member_id'] && $member) {
                $member->soc_member_id = $sanitized['soc_member_id'];
            }
            if($sanitized['soc_member_flg'] && $member) {
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

    protected function getOrNewMember(Deal $deal)
    {
        if($deal->member) {
            return $deal->member;
        }

        // 会員情報新規作成
        $member = Member::create([
            'office_id' => config('const.commons.office_id'),
            'status' => GeneralStatus::Enabled->value,
            'member_code' => Str::ulid(),
            'soc_member_id' => null,
            'soc_member_flg' => false,
            'member_type_id' => MemberType::MEMBER_TYPE_NEW,
            'name' => $deal->name ?? '',
            'kana' => $deal->kana,
            'en_name' => null,
            'zip' => $deal->zip,
            'address1' => null,
            'address2' => null,
            'tel' => $deal->tel,
            'email' => $deal->email,
            'line_id' => null,
            'line_account' => null,
            'line_email' => null,
            'image_url' => null,
            'password' => null,
            'remember_token' => null,
            'used_num' => 1,
            'memo' => null,
            'created_by' => null,
            'updated_by' => null,
        ]);

        $deal->fill([
            'member_id' => $member->id,
        ])->save();

        return $member;
    }
}
