<?php
namespace App\Http\Controllers\Manage\Commons;

class HeaderInfoStats
{
    /** @var HeaderInfoStatsRow[] */
    public $rows;
    /** @var int 入庫予定 */
    public $loadDateCount;
    /** @var int 入庫予定残り */
    public $loadDateRemainingCount;
    /** @var int 出庫予定 */
    public $unloadDateCount;
    /** @var int 出庫予定残り */
    public $unloadDateRemainingCount;

    public function loadCountRate()
    {
        if(!$this->loadDateCount) {
            return 0;
        }
        return min(100, round($this->loadDateRemainingCount / $this->loadDateCount * 100));
    }

    public function unloadCountRate()
    {
        if(!$this->unloadDateCount) {
            return 0;
        }
        return min(100, round($this->unloadDateRemainingCount / $this->unloadDateCount * 100));
    }
}
