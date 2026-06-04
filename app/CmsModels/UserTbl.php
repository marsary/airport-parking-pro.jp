<?php

/**
 * Created by Reliese Model.
 */

namespace App\CmsModels;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class UserTbl
 * 
 * @property int $user_id
 * @property int $member_id
 * @property string $member_crpt_id
 * @property int $member_flg
 * @property string $user_name_kana
 * @property string $user_name_kanji
 * @property string $user_name_roman
 * @property string $user_zip_code
 * @property string $user_addr1
 * @property string $user_addr2
 * @property string $user_mail
 * @property string $user_tel
 * @property string $mileage_no
 * @property int $user_used_num
 * @property int $user_news_flg
 * @property timestamp $ins_date
 * @property timestamp $up_date
 * @property string $user_mail2
 * @property int $easyinp_flg
 * @property string $user_tel_search
 * @property string $user_tel2
 * @property string $user_tel2_search
 * @property int $red_premium_state
 * @property string $login_pw
 * @property Carbon $red_premium_expired
 * @property int $last_car_id
 * @property int $last_car_col_id
 * @property int $last_car_number
 * @property int $last_user_num
 * @property int $red_premium_id
 *
 * @package App\CmsModels
 */
class UserTbl extends Model
{
	protected $connection = 'pgsql';
	protected $table = 'user_tbl';
	protected $primaryKey = 'user_id';
	public $timestamps = false;

	protected $casts = [
		'member_id' => 'int',
		'member_flg' => 'int',
		'user_used_num' => 'int',
		'user_news_flg' => 'int',
		'ins_date' => 'timestamp',
		'up_date' => 'timestamp',
		'easyinp_flg' => 'int',
		'red_premium_state' => 'int',
		'red_premium_expired' => 'datetime',
		'last_car_id' => 'int',
		'last_car_col_id' => 'int',
		'last_car_number' => 'int',
		'last_user_num' => 'int',
		'red_premium_id' => 'int'
	];

	protected $fillable = [
		'member_id',
		'member_crpt_id',
		'member_flg',
		'user_name_kana',
		'user_name_kanji',
		'user_name_roman',
		'user_zip_code',
		'user_addr1',
		'user_addr2',
		'user_mail',
		'user_tel',
		'mileage_no',
		'user_used_num',
		'user_news_flg',
		'ins_date',
		'up_date',
		'user_mail2',
		'easyinp_flg',
		'user_tel_search',
		'user_tel2',
		'user_tel2_search',
		'red_premium_state',
		'login_pw',
		'red_premium_expired',
		'last_car_id',
		'last_car_col_id',
		'last_car_number',
		'last_user_num',
		'red_premium_id'
	];
}
