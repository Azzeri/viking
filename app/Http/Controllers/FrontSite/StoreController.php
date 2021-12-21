<?php

namespace App\Http\Controllers\FrontSite;

use App\Models\StoreCategory;
use App\Models\StoreItem;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

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

        (request()->has(['field', 'direction']))
            ? $query->orderBy(request('field'), request('direction'))
            : $query->orderBy('name');

        if (request('filter'))
            $query->where('store_category_id', request('filter'));


        $items = $query->paginate()->withQueryString()
            ->through(fn ($storeItem) => [
                'id' => $storeItem->id,
                'name' => $storeItem->name,
                'photo_path' => $storeItem->photo_path,
                'description' => strlen($storeItem->description) > 255 ? substr($storeItem->description, 0, 255) . '...' : $storeItem->description,
                'price' => $storeItem->price,
                'category' => array(
                    'id' => $storeItem->category->id,
                    'name' => $storeItem->category->name
                )
            ]);

        $categories = StoreCategory::orderBy('name')->get()->map(fn ($category) => [
            'id' => $category->id,
            'name' => $category->name,
            'subcategories' => $category->subcategories ? $category->subcategories->map(fn ($subcategory) => [
                'id' => $subcategory->id,
                'name' => $subcategory->name,
            ]) : null
        ]);

        return inertia('Store', [
            'items' => $items,
            'categories' => $categories,
            'filters' => request()->all(['search', 'field', 'direction', 'filter']),
        ]);
    }

    public function show(StoreItem $store_item)
    {
        return inertia('ItemDetails', [
            'item' => $store_item->with('category')->first()
        ]);
    }
}
