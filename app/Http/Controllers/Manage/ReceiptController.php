<?php

namespace App\Http\Controllers\Manage;

use App\Http\Controllers\Controller;
use App\Http\Requests\Manage\PaymentUpdateRegisterRequest;
use App\Models\Deal;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReceiptController extends Controller
{
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $deal = Deal::findOrFail($id);
        /** @var Payment */
        $payment = Payment::where('deal_id', $deal->id)->firstOrFail();

        $couponDetails = [];
        $paymentDetails = collect([]);
        $couponTotal = null;
        foreach ($payment->paymentDetails as $paymentDetail) {
            if($paymentDetail->coupon()->exists()) {
                $description = $paymentDetail->coupon()->name . $paymentDetail->total_price . '円';

                $couponDetails[] = $description;
                $couponTotal += $paymentDetail->total_price;
            } else {
                $paymentDetails->push($paymentDetail);
            }
        }

        return view('manage.receipt.receipt', [
            'recieptTime' => Carbon::now(),
            'deal' => $deal,
            'payment' => $payment,
            'office' => $payment->office,
            'memberCar' => $deal->memberCar,
            'member' => $payment->member,
            'paymentDetails' => $paymentDetails,
            'couponDetails' => $couponDetails,
            'couponTotal' => $couponTotal,
        ]);
    }

    public function updateRegister(PaymentUpdateRegisterRequest $request, $id)
    {
        $payment = Payment::where('deal_id', $id)->firstOrFail();

        $payment->fill([
            'cash_register_id' => $request->input('cash_register_id'),
            'updated_by' => Auth::id(),
        ])->save();

        return redirect(route('manage.receipts.show', [$id]));
    }
}
