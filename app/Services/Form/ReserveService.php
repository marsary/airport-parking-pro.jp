<?php
namespace App\Services\Form;

use App\Enums\DealStatus;
use App\Enums\GeneralStatus;
use App\Enums\PaymentTiming;
use App\Enums\TransactionType;
use App\Http\Controllers\Form\Forms\ReserveForm;
use App\Models\Agency;
use App\Models\Deal;
use App\Models\Member;
use App\Models\MemberCar;
use App\Models\MemberType;
use Carbon\Carbon;
use ErrorException;
use Illuminate\Support\Facades\DB;

class ReserveService
{
    /** @var ReserveForm */
    public $reserve;

    /** @var Deal */
    public $deal;


    function __construct(ReserveForm $reserve)
    {
        $this->reserve = $reserve;
    }

    public function store()
    {
        $this->saveMember();
        $this->saveMemberCar();
        $this->createDeal();
    }


    protected function saveMember()
    {
        if(!$this->reserve->member) {
            throw new ErrorException("ウェブ予約では会員情報の登録は必須です。");
        }
        unset($this->reserve->member->tagMembers);
        if(isset($this->reserve->member->id)) { //会員情報更新
            $member = Member::findOrFail($this->reserve->member->id);

            return $member->fill([
                'name' => $this->reserve->name,
                'kana' => $this->reserve->kana,
                'zip' => $this->reserve->zip,
                'tel' => $this->reserve->tel,
                'email' => $this->reserve->email,
                'used_num' => 1 + $member->used_num,
            ])->save();
            
        } else { // 新規作成
             $this->reserve->member->fill([
                'office_id' => config('const.commons.form_office_id'),
                'status' => GeneralStatus::Enabled->value,
                // 'member_code' => ,  // fillMember()で設定済み
                'soc_member_id' => null,
                'soc_member_flg' => false,
                'member_type_id' => MemberType::MEMBER_TYPE_NEW,
                //     'name' => $this->reserve->name, // fillMember()で設定済み
                //     'kana' => $this->reserve->kana, // fillMember()で設定済み
                'en_name' => null,
                //     'zip' => $this->reserve->zip, // fillMember()で設定済み
                'address1' => null,
                'address2' => null,
                //     'tel' => $this->reserve->tel, // fillMember()で設定済み
                //     'email' => $this->reserve->email, // fillMember()で設定済み
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
            ])->save();
        }
    }

    protected function saveMemberCar()
    {
        $memberCar = null;
        if($this->reserve->member_car_id) {
            $memberCar = MemberCar::find($this->reserve->member_car_id);
        }

        if($memberCar) {
            $memberCar->fill([
                'office_id' => config('const.commons.form_office_id'),
                'member_id' => $this->reserve->member->id,
                'car_id' => $this->reserve->car_id,
                'car_color_id' => $this->reserve->car_color_id,
                'number' => $this->reserve->car_number,
                'default_flg' => 1, // 予約で使用したものをデフォルトに設定
                'created_by' => null,
                'updated_by' => null,
            ])->save();

            // 予約で使用したもの以外をデフォルトから外す
            DB::table('member_cars')->where('member_id', $this->reserve->member->id)
                ->whereNot('id', $memberCar->id)
                ->where('office_id', config('const.commons.form_office_id'))
                ->update(['default_flg' => false]);

        } else {
            $memberCar = MemberCar::create([
                'office_id' => config('const.commons.form_office_id'),
                'member_id' => $this->reserve->member->id,
                'car_id' => $this->reserve->car_id,
                'car_color_id' => $this->reserve->car_color_id,
                'number' => $this->reserve->car_number,
                'default_flg' => 1, // 予約で使用したものをデフォルトに設定
                'created_by' => null,
                'updated_by' => null,
            ]);

            $this->reserve->member_car_id = $memberCar->id;
        }
    }

    private function createDeal()
    {
        $this->deal = Deal::create([
            'member_id' => $this->reserve->member->id,
            'office_id' => config('const.commons.form_office_id'),
            'agency_id' => Agency::NARITA_PREMIUM_ID,
            'transaction_type' => TransactionType::PARKING->value,
            'payment_timing' => PaymentTiming::RECEPTION->value,
            'reservation_route' => null,
            'status' => DealStatus::NOT_LOADED->value,
            'reserve_code' => $this->reserve->reserve_code,
            'receipt_code' => null, // この時点では受付コードはない
            'reserve_date' => Carbon::now(),
            'load_date' => $this->reserve->load_date,
            'load_time' => $this->reserve->load_time,
            'visit_date_plan' =>null,
            'visit_time_plan' =>null,
            'unload_date_plan' => $this->reserve->unload_date_plan,
            'unload_time_plan' => null,
            'overdue' => 0,
            'arrival_flg' => $this->reserve->arrival_flg,
            'num_days' => $this->reserve->num_days,
            'num_members' => $this->reserve->num_members ?? 1,
            'name' => $this->reserve->name,
            'kana' => $this->reserve->kana,
            'zip' => $this->reserve->zip,
            'tel' => $this->reserve->tel,
            'email' => $this->reserve->email,
            'dsc_rate' => 0,
            'price' => $this->reserve->price,
            'tax' => $this->reserve->tax,
            'total_price' => $this->reserve->total_price,
            'total_tax' => $this->reserve->total_tax,
            'tax_free' => 0,
            'season_price' => $this->reserve->season_price,
            'season_price_tax' => $this->reserve->season_price_tax,
            'arr_flight_id' => $this->reserve->arr_flight_id,
            'flight_no' => $this->reserve->flight_no,
            'airline_id' => $this->reserve->airline_id,
            'member_car_id' => $this->reserve->member_car_id,
            'receipt_address' => null,
            'reserve_memo' => null,
            'reception_memo' => null,
            'remarks' => $this->setRemarkForOptionSelect($this->reserve->remarks, $this->reserve->insurance, $this->reserve->carwash, $this->reserve->newsletter),
            'remind_mail_sent_flg' => 0,
            'sync_flg' => 0,
            'synced_at' => null,
            'created_by' => null,
            'updated_by' => null,

        ]);

        if(!empty($this->reserve->dealGoodData)) {
            throw new ErrorException('ウェブ予約ではオプション商品は選択できません。');
        }
    }



    public function setRemarkForOptionSelect(string|null $remarks, bool $insurance, bool $carwash, bool $newsletter): string
    {
        $optionValues = ['H', 'W', 'メ希'];
        $remarkParts = preg_split('/\s+/u', trim((string) $remarks));
        $filteredRemarkParts = array_values(array_filter($remarkParts, static fn ($part) => !in_array($part, $optionValues, true)));
        $remarks = implode(' ', $filteredRemarkParts);

        $optionSelectData = [];
        if($insurance) {
            $optionSelectData[] = 'H';
        }
        if($carwash) {
            $optionSelectData[] = 'W';
        }
        if($newsletter) {
            $optionSelectData[] = 'メ希';
        }
        if(!empty($optionSelectData)) {
            $remarks .= ' ' . implode(' ', $optionSelectData);
        }
        return trim($remarks);
    }
}
