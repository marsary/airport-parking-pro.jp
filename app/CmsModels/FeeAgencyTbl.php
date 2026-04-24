<?php

/**
 * Created by Reliese Model.
 */

namespace App\CmsModels;

use Illuminate\Database\Eloquent\Model;

/**
 * Class FeeAgencyTbl
 * 
 * @property int $fag_id
 * @property int $fag_par_id
 * @property int $fag_sun_id
 * @property string $fag_name
 * @property string $fag_name_en
 * @property string $fag_name_men
 * @property string $fag_tel
 * @property string $fag_fax
 * @property string $fag_email
 * @property string $fag_bank_name
 * @property string $fag_bank_branch
 * @property int $fag_bank_type
 * @property string $fag_bank_account
 * @property string $fag_bank_account_name
 * @property string $fag_bank_remarks
 * @property bool $fag_outp_branch
 * @property bool $fag_outp_guide
 * @property bool $fag_outp_spec
 * @property bool $fag_fee_adjust
 * @property int $fag_fee_month
 * @property string $fag_name_men_sun
 * @property timestamp $ins_date
 * @property timestamp $up_date
 * @property string $fag_name_kana
 *
 * @package App\CmsModels
 */
class FeeAgencyTbl extends Model
{
	protected $connection = 'pgsql';
	protected $table = 'fee_agency_tbl';
	protected $primaryKey = 'fag_id';
	public $timestamps = false;

	protected $casts = [
		'fag_par_id' => 'int',
		'fag_sun_id' => 'int',
		'fag_bank_type' => 'int',
		'fag_outp_branch' => 'bool',
		'fag_outp_guide' => 'bool',
		'fag_outp_spec' => 'bool',
		'fag_fee_adjust' => 'bool',
		'fag_fee_month' => 'int',
		'ins_date' => 'timestamp',
		'up_date' => 'timestamp'
	];

	protected $fillable = [
		'fag_par_id',
		'fag_sun_id',
		'fag_name',
		'fag_name_en',
		'fag_name_men',
		'fag_tel',
		'fag_fax',
		'fag_email',
		'fag_bank_name',
		'fag_bank_branch',
		'fag_bank_type',
		'fag_bank_account',
		'fag_bank_account_name',
		'fag_bank_remarks',
		'fag_outp_branch',
		'fag_outp_guide',
		'fag_outp_spec',
		'fag_fee_adjust',
		'fag_fee_month',
		'fag_name_men_sun',
		'ins_date',
		'up_date',
		'fag_name_kana'
	];
}
