<?php

namespace App\Http\Controllers;

use App\Models\BidOrder;
use App\Models\BidOrderAccount;
use App\Models\Refund;
use Carbon\Carbon;
use Illuminate\Http\Request;

class RefundController extends Controller
{
    public function requestRefund(Request $request, $id)
    {
        if (BidOrder::find($id)) {                        
            $status = BidOrder::find($id)->bid_order_status_id;
            if((BidOrder::find($id)->project_bid()->first() && $status == 4) || 
            (BidOrder::find($id)->on_hand_bid()->first() && $status == 5)){

                $refund = $request->validate([
                    'refund_numOfDays' => 'required|numeric',
                    'refund_percentage' => 'required|numeric',
                    'refund_amount' => 'required|numeric'
                ]);
    
                if (!$refund) {
                    return response([
                        'error' => 'Invalid Refund Request!'
                    ], 400);
                }
    
                $order = BidOrder::find($id);
                Refund::create([
                    'project_id' => $order->project_id,
                    'bid_order_id' => $order->id,
                    'refund_numOfDays' => $request->refund_numOfDays,
                    'refund_percentage' => $request->refund_percentage,
                    'refund_amount' => $request->refund_amount
                ]);
    
                return response([
                    'message' => 'Refund Request Sent!'
                ], 200);
            }
            else{
                return response([
                    'error' => 'Refund Error!'
                ], 400);
            }

        } else {
            return response([
                'error' => 'Bid Order Not Found!'
            ], 400);
        }
    }

    public function approveRefund(Request $request, $id)
    {
        if (BidOrder::find($id) && Refund::where('bid_order_id', $id)->first()) {
            $refund = $request->validate([
                'bid_order_acc_paymentMethod' => 'required|string',
                'bid_order_acc_type' => 'required|string',
                'bid_order_acc_amount' => 'required|numeric',
                'bid_order_acc_bankName'  => 'nullable|string',
                'bid_order_acc_accNum' => 'nullable|string',
                'bid_order_acc_accName' => 'nullable|string',
                'bid_order_acc_remarks' => 'nullable|string',
            ]);

            if (!$refund) {
                return response([
                    'error' => 'Invalid Refund!'
                ], 400);
            }
            $order = BidOrder::find($id);
            $order->bid_order_status_id = 7;
            $order->save();

            BidOrderAccount::create([
                'bid_order_id' => $id,
                'bid_order_acc_type' => $request->bid_order_acc_type,
                'bid_order_acc_paymentMethod' => $request->bid_order_acc_paymentMethod,
                'bid_order_acc_bankName' => $request->bid_order_acc_bankName,
                'bid_order_acc_accNum' => $request->bid_order_acc_accNum,
                'bid_order_acc_accName' => $request->bid_order_acc_accName,
                'bid_order_acc_amount' => $request->bid_order_acc_amount,
                'bid_order_acc_remarks' => $request->bid_order_acc_remarks,
                'bid_order_acc_datePaid' => Carbon::now(),
            ]);

            return response([
                'message' => 'Refund Request Approved!'
            ], 200);
        } else {
            return response([
                'error' => 'Bid Order Not Found!'
            ], 400);
        }
    }

    public function confirmRefund(Request $request, $id)
    {
        if (BidOrder::find($id) && Refund::where('bid_order_id', $id)->first()) {
            $refund = $request->validate([
                'refund_receivedBy' => 'required|string',
                'refund_contactNum' => 'required|string'
            ]);

            if (!$refund) {
                return response([
                    'message' => 'Refund Confirmation Invalid!'
                ], 400);
            }

            BidOrder::find($id)->update([
                'bid_order_status_id' => 8
            ]);
                        
            Refund::where('bid_order_id', $id)->update([
                'refund_receivedBy' => $request->refund_receivedBy,
                'refund_contactNum' => $request->refund_contactNum
            ]);
            // $order->refund()->first()->refund_receivedBy = $request->refund_receivedBy;
            // $order->refund()->first()->refund_contactNum = $request->refund_contactNum;
            //$order->save();
            //$order->delete();

            BidOrder::find($id)->delete();

            return response([
                'message' => 'Refund Request Confirmed!'
            ], 200);
        } else {
            return response([
                'message' => 'Bid Order Not Found!'
            ], 400);
        }
    }
}
