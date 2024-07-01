<?php
namespace App\Http\Controllers\Manage\Forms;

use App\Models\Agency;
use App\Models\Deal;
use Carbon\Carbon;

class DealEditForm extends ManageReserveForm
{
    public $deal;

    public $member_memo;

    function __construct(Deal $deal)
    {
        $this->deal = $deal;
        $this->setMember($deal->member);

    }
}
