<?php

namespace App\Http\Controllers;

use App\Models\BidOrder;
use App\Models\BidOrderAccount;
use App\Models\ProjectBid;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ProjectBidController extends Controller
{
    public function addProjectBid(Request $request)
    {
        $order = $request->validate([
            'trader_id' => 'required',
            'distributor_id' => 'required',
            'project_id' => 'required',
            'order_dateNeededFrom' => 'required|date:after:now',
            'order_dateNeededTo' => 'required|date|after:order_dateNeededFrom',
            'order_initialPrice' => 'required|numeric',
            'project_bid_minQty' => 'required|numeric|lt:project_bid_maxQty',
            'project_bid_maxQty' => 'required|numeric',
            'project_bid_total' => 'required|numeric'
        ]);

        if (!$order) {
            return response([
                'error' => 'Invalid Order!'
            ], 400);
        }

        $newOrder = BidOrder::create([
            'trader_id' => $request->trader_id,
            'distributor_id' => $request->distributor_id,
            'bid_order_status_id' => 1,
            'project_id' => $request->project_id,
            'order_dateNeededFrom' => $request->order_dateNeededFrom,
            'order_dateNeededTo' => $request->order_dateNeededTo,
            'order_askingPrice' => $request->order_askingPrice
        ]);

        ProjectBid::create([
            'bid_order_id' => $newOrder->id,
            'project_bid_minQty' => $request->project_bid_minQty,
            'project_bid_maxQty' => $request->project_bid_maxQty,
            'project_bid_total' => $request->project_bid_total,
        ]);

        return response([
            'message' => 'Order Successful!'
        ], 200);
    }

    public function approveProjectBid(Request $request, $id)
    {
        $user = User::find(auth()->id());
        if ($user->hasRole('trader')) {
            $order = $request->validate([
                'order_negotiatedPrice' => 'required|numeric',
                'order_datePlaced' => 'required|date', //from created_at table
                'project_bid_total' => 'required|numeric',
                'order_dpDueDate' => 'required|date|after:order_datePlaced'
            ]);

            if (!$order) {
                return response([
                    'error' => 'Invalid Order Approval!'
                ], 400);
            }
        }

        $bidOrder = BidOrder::find($id);
        if (!$bidOrder) {
            return response([
                'error' => 'Bid Order Not Found!'
            ], 400);
        }

        if ($user->hasRole('trader')) {
            ProjectBid::where('bid_order_id', $id)->update([
                'project_bid_total' => $request->project_bid_total
            ]);
            $bidOrder->order_dpDueDate = $request->order_dpDueDate;
            $bidOrder->bid_order_status_id = 2;
            $bidOrder->save();
        } else { // Distributor Role                 
            $bidOrder->bid_order_status_id = 3;
            $bidOrder->save();
        }
        return response([
            'message' => 'Order Approved!'
        ], 200);
    }

    public function paymentProjectBid(Request $request, $id)
    {
        $user = User::find(auth()->id());
        if ($user->hasRole('distributor')) {
            $order = $request->validate([
                'bid_order_acc_paymentMethod' => 'required',
                'bid_order_acc_bankName' => 'required',
                'bid_order_acc_accNum' => 'required',
                'bid_order_acc_accName' => 'required',
                'bid_order_acc_amount' => 'required|numeric',
            ]);

            if (!$order) {
                return response([
                    'error' => 'Invalid Order!'
                ], 400);
            }

            $order = BidOrder::find($id);
            if (!$order) {
                return response([
                    'error' => 'Bid Order Not Found!'
                ], 400);
            }
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
                'message' => 'First Payment Delivered!'
            ], 200);
        } else {
            $order = BidOrder::find($id);
            $order->bid_order_status_id = 4;
            $order->save();
            return response([
                'message' => 'First Payment Confirmed!'
            ], 200);
        }
    }

    public function deliverProjectBid(Request $request, $id)
    {
        $user = User::find(auth()->id());
        $status = BidOrder::find($id)->bid_order_status_id;
        if ($user->hasRole('trader')) {
            if ($status == 4) {
                $order = $request->validate([
                    'order_finalQty' => 'required|numeric',
                    'order_finalPrice' => 'required|numeric',
                    'order_finalTotal' => 'required|numeric'
                ]);
                if (!$order) {
                    return response([
                        'error' => 'Bid Order Not Found!'
                    ], 400);
                }
                $order = BidOrder::find($id);
                $order->bid_order_status_id = 5;
                $order->save();
                return response([
                    'message' => 'Ready for Delivery!'
                ], 200);
            } else if ($status == 5) {
                $order = BidOrder::find($id);
                $order->bid_order_status_id = 6;
                $order->save();
                return response([
                    'message' => 'Final Payment Confirmed!'
                ], 200);

                // ----------------- SALES MODULE PART --------------- //
            }
        } else { // when status is 5
            $order = $request->validate([
                'bid_order_acc_paymentMethod' => 'required',
                'bid_order_acc_bankName' => 'required',
                'bid_order_acc_accNum' => 'required',
                'bid_order_acc_accName' => 'required',
                'bid_order_acc_amount' => 'required|numeric',
            ]);
            if (!$order) {
                return response([
                    'error' => 'Invalid Order!'
                ], 400);
            }
            $order = BidOrder::find($id);
            if (!$order) {
                return response([
                    'error' => 'Bid Order Not Found!'
                ], 400);
            }
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
                'message' => 'Final Payment Delivered!'
            ], 200);
        }
    }
}
