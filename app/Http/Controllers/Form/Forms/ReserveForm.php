<?php
namespace App\Http\Controllers\Form\Forms;

use App\Http\Controllers\Forms\ReserveFormBase;
use App\Models\Member;
use Illuminate\Support\Str;

class ReserveForm extends ReserveFormBase
{
    public bool $insurance = false;
    public bool $carwash = false;

    public function setMember(?Member $member = null)
    {
        parent::setMember($member);
    }

    public function fillMember()
    {
        if(!$this->member) {
            $this->member = new Member();
            $this->member->member_code = Str::ulid();
            $this->member->office_id = $this->office_id;
        }
        $this->member->fill([
            'name' => $this->name,
            'kana' => $this->kana,
            'zip' => $this->zip,
            'tel' => $this->tel,
            'email' => $this->email,
        ]);
    }

    public function handleGoodsAndTotals()
    {
        parent::handleGoodsAndTotals();

        // TODO クーポンの処理
    }

    public function setRemarkForOptionSelect()
    {
        $this->remarks = str_replace('旅行保険の加入検討。', '', $this->remarks);
        $this->remarks = str_replace('洗車を検討。', '', $this->remarks);

        $optionSelectData = [];
        if($this->insurance) {
            $optionSelectData[] = '旅行保険の加入検討。';
        }
        if($this->carwash) {
            $optionSelectData[] = '洗車を検討。';
        }
        if(!empty($optionSelectData)) {
            $this->remarks .= implode('', $optionSelectData);
        }
    }
}
