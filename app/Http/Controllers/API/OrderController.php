<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderDetail;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{

    /**
     * Create new orders.
     */
    public function store(Request $request)
    {
        DB::transaction();
        try {
            //get carts by user_id
            $carts = Cart::where('user_id', Auth::user()->id)->get();

            $record = [];

            foreach ($carts as $row) {
                $record = [
                    'food_id' => $row->food_id,
                    'quantity' => $row->quantity,
                    'price' => $row->price,
                    'user_id' => Auth::user()->id,
                ];
                $records[] = $record;
            }

            // insert OrderDetail
            OrderDetail::inser($records);

            // create code
            $code = 'AFD-' . str_replace('.', '-', microtime(true));
            // insert order
            $order = Order::create([
                'transaction_code' => $code,
                'quantity' => $request->quantity,
                'price' => $request->price,
                'status' => $request->status,
            ]);

            // delete carts
            Cart::where('user_id', Auth::user()->id)->delete();

            DB::commit();

            return $this->sendResponse($order, 'Create order success!');
        } catch (Exception $e) {
            DB::rollBack();
            return $this->sendError('Failed to create order!', $e->getMessage(), 505);
        }
    }

    /**
     * Show order data.
     */
    public function show(string $code)
    {
        $order = Order::where('transaction_code', $code)->get();

        $data = OrderResource::collection($order);

        return $this->sendResponse($data, 'Get order success!');
    }
}
