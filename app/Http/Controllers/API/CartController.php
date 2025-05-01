<?php

namespace App\Http\Controllers\API;

use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Resources\CartResource;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    /**
     * List cart.
     */
    public function index()
    {
        $cart = Cart::where('user_id', Auth::user()->id)->get();

        $data = CartResource::collection($cart);

        return $this->sendResponse($data, 'Get menu success!');
    }

    /**
     * Save food to cart.
     */
    public function store(Request $request)
    {
        $request->validate([
            'food_id' => 'required',
        ]);

        // Update if have, or create new if havent
        $cart = Cart::updateOrCreate(
            [
                'food_id' => $request->food_id,
                'user_id' => Auth::user()->id,
            ],
            [
                'quantity' => DB::raw('quantity + ' . $request->quantity),
                'price' => $request->price,
            ]
        );

        return $this->sendResponse(null, 'Save menu success!');
    }

    /**
     * delete data cart.
     */
    public function destroy(string $id)
    {
        $cart = Cart::find($id);

        if ($cart) $cart->delete();

        return $this->sendResponse(null, 'Delete menu success!', 200);
    }
}
