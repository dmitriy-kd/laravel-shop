<?php

namespace App\Http\Controllers;

use App\Category;
use App\Http\Requests\ProductsFilterRequest;
use App\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MainController extends Controller
{
    public function index(ProductsFilterRequest $request)
    {
//        dd($request->all());
        Log::channel('single')->info($request->ip());
//        $productsQuery = Product::query();
        $productsQuery = Product::with('category'); //метод with позволяет снизить число запросов чем в query()
        if ($request->filled('price_from')) {
            $productsQuery->where('price', '>=', $request->price_from);
        }

        if ($request->filled('price_to')) {
            $productsQuery->where('price', '<=', $request->price_to);
        }

        foreach (['hit', 'new', 'recommend'] as $field)
        if ($request->has($field)) {
//            $productsQuery->where($field, 1); стандартный вариант
            $productsQuery->$field(); // вариант через scope
        }

        $products = $productsQuery->paginate(3)->withPath('?' . $request->getQueryString());
        return view('index', compact('products'));
    }

    public function product($category, $productCode = null)
    {
        $product = Product::where('code', $productCode)->first();
        return view('product', ['product' => $product]);
    }

    public function category($code)
    {
        $category = Category::where('code', $code)->first();
//        $products = Product::where('category_id', $category->id)->get();

        return view('category', compact('category'/*, 'products'*/));
    }

    public function categories()
    {
        $categories = Category::get();
        return view('categories', compact('categories'));
    }

    public function card($id)
    {
        $product = Product::where('id', $id)->first();
        return view('card', compact('product'));
    }

}
