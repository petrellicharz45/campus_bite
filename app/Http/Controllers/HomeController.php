<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request): View
    {
        $categories = Category::query()->orderBy('name')->get();
        $selectedCategory = $request->string('category')->toString();
        $search = $request->string('search')->toString();

        $products = Product::query()
            ->with('category')
            ->where('is_available', true)
            ->when($selectedCategory, function ($query, string $category): void {
                $query->whereHas('category', fn ($categoryQuery) => $categoryQuery->where('slug', $category));
            })
            ->when($search, function ($query, string $term): void {
                $query->where(function ($nestedQuery) use ($term): void {
                    $nestedQuery
                        ->where('name', 'like', "%{$term}%")
                        ->orWhere('short_description', 'like', "%{$term}%");
                });
            })
            ->latest()
            ->take(6)
            ->get();

        $featuredProducts = Product::query()
            ->with('category')
            ->where('is_available', true)
            ->where('is_featured', true)
            ->take(4)
            ->get();

        return view('home', [
            'categories' => $categories,
            'featuredProducts' => $featuredProducts,
            'products' => $products,
            'selectedCategory' => $selectedCategory,
            'search' => $search,
            'pageTitle' => 'Campus Bites and Canteen',
        ]);
    }

    public function menu(Request $request): View
    {
        $categories = Category::query()->orderBy('name')->get();
        $selectedCategory = $request->string('category')->toString();
        $search = $request->string('search')->toString();

        $products = Product::query()
            ->with('category')
            ->where('is_available', true)
            ->when($selectedCategory, function ($query, string $category): void {
                $query->whereHas('category', fn ($categoryQuery) => $categoryQuery->where('slug', $category));
            })
            ->when($search, function ($query, string $term): void {
                $query->where(function ($nestedQuery) use ($term): void {
                    $nestedQuery
                        ->where('name', 'like', "%{$term}%")
                        ->orWhere('short_description', 'like', "%{$term}%")
                        ->orWhere('description', 'like', "%{$term}%");
                });
            })
            ->orderBy('name')
            ->paginate(9)
            ->withQueryString();

        return view('menu', [
            'categories' => $categories,
            'products' => $products,
            'selectedCategory' => $selectedCategory,
            'search' => $search,
            'pageTitle' => 'Browse the Menu',
        ]);
    }
}
