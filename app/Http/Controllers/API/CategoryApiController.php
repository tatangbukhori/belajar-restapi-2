<?php

namespace App\Http\Controllers\API;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;

class CategoryApiController extends Controller
{
    public function index()
    {
        $categories = Category::all();

        return $this->sendResponse(CategoryResource::collection($categories), 'Get category success!');
    }
}
