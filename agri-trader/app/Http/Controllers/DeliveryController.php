<?php

namespace App\Http\Controllers;

use App\Models\BidOrder;
use App\Models\BidOrderAccount;
use App\Models\Delivery;
use App\Models\ProduceInventory;
use App\Models\ProduceYield;
use App\Models\Sale;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DeliveryController extends Controller
{
    public function deliverProjectBid(Request $request, $id)
    {
        $user = User::find(auth()->id());
        if (BidOrder::find($id)) {
            $status = BidOrder::find($id)->bid_order_status_id;
            $acc = BidOrderAccount::where([['bid_order_id', '=', $id], ['bid_order_acc_type', '=', 'Final Payment']])->first();
            if ($user->hasRole('trader')) {
                if ($status == 4) {
                    $order = $request->validate([
                        'order_finalQty' => 'required|numeric',
                        'order_finalPrice' => 'required|numeric',
                        'order_finalTotal' => 'required|numeric',
                        'delivery_date' => 'required|date'
                    ]);
                    if (!$order) {
                        return response([
                            'error' => 'Bid Order Not Found!'
                        ], 400);
                    }

                    $order = BidOrder::find($id);
                    $order->bid_order_status_id = 5;
                    $order->order_finalQty = $request->order_finalQty;
                    $order->order_finalPrice = $request->order_finalPrice;
                    $order->order_finalTotal = $request->order_finalTotal;
                    $order->save();

                    Delivery::create([
                        'bid_order_id' => $id,
                        'delivery_status' => 'Pending',
                        'delivery_date' => $request->delivery_date
                    ]);


                    return response([
                        'message' => 'Ready for Delivery!'
                    ], 200);
                } else if ($status == 5 && $acc) {
                    $order = BidOrder::find($id);
                    $order->bid_order_status_id = 6;
                    $order->save();

                    Delivery::where('bid_order_id', $id)->update([
                        'delivery_status' => 'Received'
                    ]);

                    $yield = ProduceYield::where([
                        ['project_id', '=', $order->project_id],
                        ['produce_yield_class', '=', $order->order_grade]
                    ])->first();

                    $qty = ProduceInventory::where('produce_yield_id', $yield->id)->first()->produce_inventory_qtyOnHand;



                    ProduceInventory::where('produce_yield_id', $yield->id)->update([
                        'produce_inventory_qtyOnHand' => $qty - $order->order_finalQty
                    ]);

                    $inventory = ProduceInventory::where('produce_yield_id', $yield->id)->first();

                    Sale::create([
                        'bid_order_id' => $order->id,
                        'sale_type' => 'Sales (Project Bid)',
                        'sale_qty' => $order->order_finalQty,
                        'sale_stockLeft' => $inventory->produce_inventory_qtyOnHand,
                        'sale_price' => $order->order_finalPrice,
                        'sale_total' => $order->order_finalTotal
                    ]);

                    $order->delete();

                    return response([
                        'message' => 'Final Payment Confirmed!'
                    ], 400);
                } else if ($status == 6 && $acc) {
                    return response([
                        'error' => 'Final Payment Already Confirmed!'
                    ], 400);
                } else {
                    return response([
                        'error' => 'Error!'
                    ], 400);
                }
            } else { // distributor                
                if ($status == 5 && !$acc) {
                    $order = $request->validate([
                        'bid_order_acc_paymentMethod' => 'required|string',
                        'bid_order_acc_type' => 'required|string',
                        'bid_order_acc_amount' => 'required|numeric',
                        'bid_order_acc_bankName'  => 'nullable|string',
                        'bid_order_acc_accNum' => 'nullable|string',
                        'bid_order_acc_accName' => 'nullable|string',
                        'bid_order_acc_remarks' => 'nullable|string',
                    ]);
                    if (!$order) {
                        return response([
                            'error' => 'Invalid Order!'
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
                } else {
                    return response([
                        'error' => 'Error!'
                    ], 400);
                }
            }
        } else {
            return response([
                'error' => 'Bid Order Not Found!'
            ], 400);
        }
    }

    public function deliverOnHandBid(Request $request, $id)
    {
        $user = User::find(auth()->id());
        $order = BidOrder::find($id);

        if ($order) {
            $status = BidOrder::find($id)->bid_order_status_id;
            $acc = BidOrderAccount::where([['bid_order_id', '=', $id], ['bid_order_acc_type', '=', 'Final Payment']])->first();
            if ($user->hasRole('trader')) {
                if ($status == 4) {
                    $order = $request->validate([
                        'delivery_date' => 'required|date'
                    ]);

                    if (!$order) {
                        return response([
                            'error' => 'Bid Order Not Found!'
                        ], 400);
                    }
                    $order = BidOrder::find($id);

                    $order->bid_order_status_id = 5;
                    $order->order_finalQty = $order->on_hand_bid()->first()->on_hand_bid_qty;
                    $order->order_finalPrice = $order->order_negotiatedPrice;
                    $order->order_finalTotal = $order->on_hand_bid()->first()->on_hand_bid_total;
                    $order->save();

                    Delivery::create([
                        'bid_order_id' => $id,
                        'delivery_status' => 'Pending',
                        'delivery_date' => $request->delivery_date
                    ]);
                    return response([
                        'message' => 'Ready for Delivery!'
                    ], 200);
                } else if ($status == 5 && $acc) {
                    $order->bid_order_status_id = 6;
                    $order->order_finalQty = $order->on_hand_bid()->first()->on_hand_bid_qty;
                    $order->order_finalPrice = $order->order_negotiatedPrice;
                    $order->order_finalTotal = $order->on_hand_bid()->first()->on_hand_bid_total;
                    $order->save();

                    Delivery::where('bid_order_id', $id)->update([
                        'delivery_status' => 'Received'
                    ]);

                    $yield = ProduceYield::where([
                        ['project_id', '=', $order->project_id],
                        ['produce_yield_class', '=', $order->order_grade]
                    ])->first();

                    $qty = ProduceInventory::where('produce_yield_id', $yield->id)->first()->produce_inventory_qtyOnHand;



                    ProduceInventory::where('produce_yield_id', $yield->id)->update([
                        'produce_inventory_qtyOnHand' => $qty - $order->order_finalQty
                    ]);

                    $inventory = ProduceInventory::where('produce_yield_id', $yield->id)->first();

                    Sale::create([
                        'bid_order_id' => $order->id,
                        'sale_type' => 'Sales (On-Hand Bid)',
                        'sale_qty' => $order->order_finalQty,
                        'sale_stockLeft' => $inventory->produce_inventory_qtyOnHand,
                        'sale_price' => $order->order_finalPrice,
                        'sale_total' => $order->order_finalTotal
                    ]);

                    $order->delete();

                    // ----------------- SALES MODULE PART --------------- //

                    return response([
                        'message' => 'Final Payment Confirmed!'
                    ], 200);
                } else if ($status == 6 && $acc) {
                    return response([
                        'error' => 'Final Payment Already Confirmed!'
                    ], 400);
                } else {
                    return response([
                        'error' => 'Error!'
                    ], 400);
                }
            } else { // when status is 5
                if ($status == 5 && !$acc) {
                    $order = $request->validate([
                        'bid_order_acc_paymentMethod' => 'required|string',
                        'bid_order_acc_type' => 'required|string',
                        'bid_order_acc_amount' => 'required|numeric',
                        'bid_order_acc_bankName'  => 'nullable|string',
                        'bid_order_acc_accNum' => 'nullable|string',
                        'bid_order_acc_accName' => 'nullable|string',
                        'bid_order_acc_remarks' => 'nullable|string',
                    ]);

                    if (!$order) {
                        return response([
                            'error' => 'Invalid Order!'
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
                } else {
                    return response([
                        'error' => 'Error!'
                    ], 400);
                }
            }
        } else {
            return response([
                'error' => 'Bid Order Not Found!'
            ], 400);
        }
    }
}
