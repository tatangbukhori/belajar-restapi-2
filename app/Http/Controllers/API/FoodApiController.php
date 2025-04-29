<?php

namespace App\Http\Controllers\API;

use Exception;
use App\Models\Food;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\FoodResource;
use App\Http\Requests\StoreFoodRequest;

class FoodApiController extends Controller
{
    /**
     * Get data foods.
     */
    public function index()
    {
        $foods = Food::paginate(2);

        return $this->sendResponse(FoodResource::collection($foods)->resource, 'Get data success!');
    }

    /**
     * Save foods.
     */
    public function store(StoreFoodRequest $request)
    {
        $request->validated();

        $food = Food::create([
            'name' => $request->name,
            'slug' => $request->slug,
            'price' => $request->price,
            'description' => $request->description,
            'image' => $request->image,
            'category_id' => $request->category_id,
            'user_id' => $request->user_id,
        ]);

        return $this->sendResponse(new FoodResource($food), 'Save data success!');
    }

    /**
     * Show data foods.
     */
    public function show($id)
    {
        $food = Food::find($id);

        return $this->sendResponse(new FoodResource($food), 'Show data food success!');
    }

    /**
     * Update data foods.
     */
    public function update(Request $request, $id)
    {
        $food = Food::find($id);

        $request->validate([
            'name' => 'required|min:3',
            'slug' => 'required|lowercase|unique:food',
            'price' => 'required'
        ]);

        $food->update([
            'name' => $request->name,
            'slug' => $request->slug,
            'price' => $request->price,
            'description' => $request->description,
        ]);

        return $this->sendResponse(new FoodResource($food), 'Update data food success!');
    }

    /**
     * Delete data foods.
     */
    public function destroy($id)
    {
        try {
            $food = Food::find($id);
            $food->delete();

            return $this->sendResponse(null, 'Delete data food success!');
        } catch (Exception $e) {
            return $this->sendError('Delete data food failed!', $e->getMessage());
        }
    }
}
