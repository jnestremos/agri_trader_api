<?php

namespace App\Http\Controllers;

use App\Models\BidOrder;
use App\Models\OnHandBid;
use App\Models\User;
use Illuminate\Http\Request;

class OnHandBidController extends Controller
{
    public function addOnHandBid(Request $request){
        $order = $request->validate([
            'trader_id' => 'required',            
            'project_id' => 'required',
            'order_dateNeededFrom' => 'required|date:after:now',
            'order_dateNeededTo' => 'required|date|after:order_dateNeededFrom',
            'order_initialPrice' => 'required|numeric',            
            'on_hand_bid_qty' => 'required|numeric',
            'on_hand_bid_total' => 'required|numeric'
        ]);

        if (!$order) {
            return response([
                'error' => 'Invalid Order!'
            ], 400);
        }

        $newOrder = BidOrder::create([
            'trader_id' => $request->trader_id,
            'distributor_id' => User::find(auth()->id())->distributor()->first()->id,
            'bid_order_status_id' => 1,
            'project_id' => $request->project_id,
            'order_dateNeededFrom' => $request->order_dateNeededFrom,
            'order_dateNeededTo' => $request->order_dateNeededTo,
            'order_initialPrice' => $request->order_initialPrice
        ]);

        OnHandBid::create([
            'bid_order_id' => $newOrder->id,
            'on_hand_bid_qty' => $request->on_hand_bid_qty,            
            'on_hand_bid_total' => $request->on_hand_bid_total,
        ]);

        return response([
            'message' => 'Order Successful!'
        ], 200);
    }
    public function approveOnHandBid(Request $request, $id)
    {
        $user = User::find(auth()->id());
        if ($user->hasRole('trader')) {
            $order = $request->validate([
                'order_negotiatedPrice' => 'required|numeric',
                'order_datePlaced' => 'required|date', //from created_at table
                'on_hand_bid_total' => 'required|numeric',
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
            OnHandBid::where('bid_order_id', $id)->update([
                'on_hand_bid_total' => $request->on_hand_bid_total
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
}
