<?php
namespace App\Http\Controllers\Manage\Commons;

class HeaderInfoStatsRow
{
    /** @var string ラベル */
    public $label;
    /** @var int 一日の実績額 */
    public $totalOnTheDay;
    /** @var int 一日の目標額 */
    public $targetOnTheDay;

    function __construct($label, $totalOnTheDay, $targetOnTheDay)
    {
        $this->label = $label;
        $this->totalOnTheDay = $totalOnTheDay ?? 0;
        $this->targetOnTheDay = $targetOnTheDay;
    }

    public function renderPrices():string
    {
        return '¥'. $this->formatPrice($this->totalOnTheDay) .'- / ¥'. $this->formatPrice($this->targetOnTheDay) .'-';
    }

    private function formatPrice($number):string
    {
        if(!is_numeric($number)) {
            return '';
        }
        return number_format($number);
    }
}
