<?php
namespace App\Services\Ledger;

use App\Models\AgencyRecord;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class AgencyRecordService
{
    public $deals;

    function __construct(Collection $deals)
    {
        $this->deals = $deals;
    }

    public function saveData()
    {
        $savedCount = 0;


        DB::transaction(function () use(&$savedCount) {
            foreach ($this->deals as $deal) {
                $row = new AgencyRecord();

                $row->office_id = $deal->office_id;
                $row->agency_id = $deal->agency_id;
                $row->agency_name = $deal->agency->name;
                $row->receipt_code = $deal->receipt_code ?? '';
                $row->member_code = $deal->member->member_code;
                $row->deal_id = $deal->id;
                $row->reserve_name = $deal->name;
                $row->reserve_name_kana = $deal->kana;
                $row->load_date = $deal->load_date;
                $row->unload_date = $deal->unload_date;
                $row->unload_date_plan = $deal->unload_date_plan;
                $row->unload_time_plan = $deal->unload_time_plan;

                $row->airline_name = $deal->arrivalFlight?->airline?->name;
                $row->dep_airport_name = $deal->arrivalFlight?->depAirport?->name;
                $row->flight_name = $deal->arrivalFlight?->name;
                $row->arrive_date = $deal->arrivalFlight?->arrive_date;
                $row->arrive_time = $deal->arrivalFlight?->arrive_time;
                $row->car_name = $deal->memberCar?->car?->name;
                $row->car_maker_name = $deal->memberCar?->car?->carMaker?->name;
                $row->car_color_name = $deal->memberCar?->carColor?->name;
                $row->car_number = $deal->memberCar?->number;


                $row->save();
                $savedCount++;
            }
        });



        return $savedCount;
    }

}
