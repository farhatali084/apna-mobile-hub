<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the products.
     */
    public function index(Request $request)
    {
        $query = Product::query();

        // 1. Search filter
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('description', 'like', '%' . $search . '%');
            });
        }

        // 2. Category filter
        $currentCategory = null;
        $filterGroups = collect();

        if ($request->has('category') && $request->category != '') {
            $currentCategory = Category::where('slug', $request->category)->first();
            if ($currentCategory) {
                $query->where('category_id', $currentCategory->id);
                // Load only the values mapped to this category and filter out empty groups
                $filterGroups = $currentCategory->filterGroups()
                    ->with(['values' => function ($q) use ($currentCategory) {
                        $q->whereIn('filter_values.id', $currentCategory->filterValues->pluck('id'));
                    }])
                    ->get()
                    ->filter(fn ($group) => $group->values->isNotEmpty());
            }
        }

        // 3. Generic tags filter (AND-across-groups, OR-within-group)
        if ($request->has('filters') && is_array($request->filters)) {
            $selectedValues = \App\Models\FilterValue::whereIn('id', $request->filters)->get();
            $groupedSelected = $selectedValues->groupBy('filter_group_id');
            
            foreach ($groupedSelected as $groupId => $values) {
                $valueIds = $values->pluck('id')->toArray();
                $query->whereHas('filterValues', function($q) use ($valueIds) {
                    $q->whereIn('filter_values.id', $valueIds);
                });
            }
        }

        // 4. In Stock filter
        if ($request->has('in_stock') && $request->in_stock == '1') {
            $query->where('stock', '>', 0);
        }

        // 5. Price range filter
        if ($request->filled('price_min')) {
            $query->where('price', '>=', (float) $request->price_min);
        }
        if ($request->filled('price_max')) {
            $query->where('price', '<=', (float) $request->price_max);
        }

        $products = $query->latest()->paginate(12)->withQueryString();
        
        // Get all categories for the navigation pills
        $categories = Category::all();

        // Get active client reviews
        $reviews = \App\Models\Review::where('is_active', true)->orderBy('display_order')->get();
 
        return view('products.index', compact('products', 'categories', 'currentCategory', 'filterGroups', 'reviews'));
    }

    /**
     * Display the specified product.
     */
    public function show($slug)
    {
        $product = Product::where('slug', $slug)->firstOrFail();
        
        // Get related products (same category, excluding current product)
        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->limit(4)
            ->get();

        return view('products.show', compact('product', 'relatedProducts'));
    }
}
