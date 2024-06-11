<?php

namespace App\Http\Controllers\Manage;

use App\Http\Controllers\Manage\Controller;
use App\Http\Controllers\Manage\Forms\ManageReserveForm;
use App\Http\Requests\Manage\EntryDateRequest;
use App\Models\Member;
use App\Services\PriceTable;
use Illuminate\Http\Request;
class ReservesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    public function entryDate()
    {
        $reserve = $this->getReserveForm();

        return view('manage.reserves.entry_date', [
            'reserve' => $reserve,
        ]);
    }

    public function postEntryDate(EntryDateRequest $request)
    {
        $reserve = $this->getReserveForm();
        $reserve->fill($request->all());

        $table = PriceTable::getPriceTable($reserve->load_date, $reserve->unload_date_plan, $reserve->coupon_ids, $reserve->agency_id);
        $reserve->fill([
            'price' => $table->discountedSubTotal,
            'tax' => $table->tax,
            'num_days' => $table->numDays,
            'total_tax' => $table->tax,
            'total_price' => $table->discountedSubTotal,
        ]);
        session()->put('manage_reserve', $reserve);
        return redirect()->route('manage.reserves.entry_info');
    }

    public function entryInfo()
    {
        $reserve = $this->getReserveForm();

        return view('manage.reserves.entry_info', [
            'reserve' => $reserve,
        ]);
    }

    public function confirm()
    {
        $reserve = $this->getReserveForm();

        return view('manage.reserves.confirm', [
            'reserve' => $reserve,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $reserve = $this->getReserveForm();
        return redirect(route('manage.deals.index'));
    }


    private function getReserveForm():ManageReserveForm
    {
        $reserve = session()->get('manage_reserve');
        if(!$reserve) {
            $reserve = new ManageReserveForm();
        }
        return $reserve;
    }


    private function getMember(Request $request)
    {
        $kana = $request->input('kana');
        $tel = $request->input('tel');

        return Member::where('kana', $kana)->where('tel', $tel)->first();
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
