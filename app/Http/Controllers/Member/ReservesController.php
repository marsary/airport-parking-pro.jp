<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Member\Forms\ReserveForm;
use App\Http\Requests\Member\EntryCarRequest;
use App\Http\Requests\Member\EntryDateRequest;
use App\Http\Requests\Member\EntryInfoRequest;
use App\Http\Requests\Member\OptionSelectRequest;
use App\Models\CarMaker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReservesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
    }

    public function entryDate()
    {
        $reserve = $this->getReserveForm();

        return view('member.reserves.entry_date', [
            'reserve' => $reserve
        ]);
    }

    public function postEntryDate(EntryDateRequest $request)
    {
        $reserve = $this->getReserveForm();
        $reserve->fill($request->all());
        session()->put('reserve', $reserve);
        return redirect()->route('reserves.entry_info');
    }

    public function entryInfo()
    {
        $reserve = $this->getReserveForm();

        return view('member.reserves.entry_info', [
            'reserve' => $reserve
        ]);
    }

    public function postEntryInfo(EntryInfoRequest $request)
    {
        $reserve = $this->getReserveForm();
        $reserve->fill($request->all());
        session()->put('reserve', $reserve);
        return redirect()->route('reserves.entry_car');
    }

    public function entryCar()
    {
        $reserve = $this->getReserveForm();
        $carMakers = CarMaker::select('name', 'id')->get();

        return view('member.reserves.entry_car', [
            'reserve' => $reserve,
        ]);
    }

    public function postEntryCar(EntryCarRequest $request)
    {
        $reserve = $this->getReserveForm();
        $reserve->fill($request->all());
        session()->put('reserve', $reserve);
        return redirect()->route('reserves.option_select');
    }

    public function optionSelect()
    {
        $reserve = $this->getReserveForm();

        return view('member.reserves.option_select', [
            'reserve' => $reserve,
        ]);
    }

    public function postOptionSelect(OptionSelectRequest $request)
    {
        $reserve = $this->getReserveForm();
        $reserve->fill($request->all());
        session()->put('reserve', $reserve);
        return redirect()->route('reserves.confirm');
    }

    public function confirm()
    {
        $reserve = $this->getReserveForm();

        return view('member.reserves.confirm', [
            'reserve' => $reserve,
        ]);
    }


    private function getReserveForm():ReserveForm
    {
        $reserve = session()->get('reserve');
        if(!$reserve) {
            $reserve = new ReserveForm();
            $member = Auth::guard('members')->user();
            $reserve->setMember($member);
        }
        return $reserve;
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store()
    {
        $reserve = $this->getReserveForm();

        return redirect()->route('reserves.complete');
    }


    public function complete(Request $request)
    {
        return view('member.reserves.complete', [
            'reserveCode' => $request->code
        ]);
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
