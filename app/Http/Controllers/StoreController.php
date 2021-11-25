<?php

namespace App\Http\Controllers;

use App\Models\StoreCategory;
use App\Models\StoreItem;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    public function index()
    {
        request()->validate([
            'direction' => ['in:asc,desc'],
            'field' => ['in:name,price']
        ]);

        $query = StoreItem::query();

        if (request('search')) {
            $query->where('name', 'ILIKE', '%' . request('search') . '%');
        }

        if (request()->has(['field', 'direction'])) {
            $query->orderBy(request('field'), request('direction'));
        } else
            $query->orderBy('name');

        if (request('filter')) 
            $query->where('store_category_id', request('filter'));


        $items = $query->paginate(12)->withQueryString()
            ->through(fn ($storeItem) => [
                'id' => $storeItem->id,
                'name' => $storeItem->name,
                'photo_path' => $storeItem->photo_path,
                'description' => $storeItem->description,
                'quantity' => $storeItem->quantity,
                'price' => $storeItem->price,
                // 'store_category_id' => $storeItem->store_category_id,
                'category_name' => $storeItem->category->name,
                // 'category_id' => $storeItem->category->id,
            ]);

        $categories = StoreCategory::orderBy('name')->get()->map(fn ($category) => [
            'id' => $category->id,
            'name' => $category->name,
            'subcategories' => $category->subcategories ? array_column($category->subcategories->toArray(), 'name') : null,
            // 'subcategoriesIds' => $category->subcategories ? array_column($category->subcategories->toArray(), 'id') : null,
            'parent_category_id' => $category->parentCategory ? $category->parentCategory->id : null
        ]);

        // dd($categories);

        return inertia('Store', [
            'items' => $items,
            'categories' => $categories,
            'filters' => request()->all(['search', 'field', 'direction', 'filter']),
        ]);
    }
}
