<?php

namespace App\Http\Controllers\Api;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryController extends Controller
{
    public function show($category): JsonResource
    {
        // $category = Category::find($category);
        // $category = Category::whereSlug($category);
        $category = Category::where('slug', $category)->firstOrFail();

        return CategoryResource::make($category);
    }

    public function index()
    {
        $categories = Category::all();
        $categories = Category::jsonPaginate();

        return CategoryResource::collection($categories);
    }
}
