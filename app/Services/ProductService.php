<?php

namespace App\Services;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProductService
{
    public function getUserProducts()
    {
        $query = Product::with('vendor')
            ->whereHas('vendor', fn($q) => $q->where('user_id', Auth::id()));

        if ($search = request('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }
       
        return $query->paginate(request('per_page', 5));
    }

    public function createProduct(StoreProductRequest $request)
    {
        $vendor = Auth::user()->vendor;

        if (!$vendor) {
            abort(404, 'You do not have a registered vendor yet.');
        }

        return DB::transaction(fn () =>
            Product::create([
                'vendor_id' => $vendor->id,
                'name' => $request->name,
                'description' => $request->description,
                'price' => $request->price,
            ])
        , 5);
    }

    public function updateProduct(UpdateProductRequest $request, Product $product)
    {
        return DB::transaction(function () use ($request, $product) {
            $product->update($request->validated());
            return $product->fresh();
        }, 5);
    }

    public function deleteProduct(Product $product): void
    {
        DB::transaction(fn () => $product->delete(), 5);
    }
}
