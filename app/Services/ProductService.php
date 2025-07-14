<?php

namespace App\Services;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Product;
use Illuminate\Database\Eloquent\ModelNotFoundException;
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
                $q
                    ->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        return $query->paginate(request('per_page', 5));
    }

    public function createProduct(StoreProductRequest $request)
    {
        return DB::transaction(fn() =>
                Product::create([
                    'vendor_id' => $vendor->id,
                    'name' => $request->name,
                    'description' => $request->description,
                    'price' => $request->price,
                ]),
            5);
    }

    public function updateProduct(UpdateProductRequest $request, $product)
    {
        $product->update($request->validated());
        return $product;
    }

    public function deleteProduct($product): void
    {
        $product->delete();
    }

    public function productFindOrFail($id)
    {
        return Product::findOrFail($id);
    }

    public function authorizeUser(Product $product): bool
    {
        return $product->vendor->user_id === Auth::id();
    }

    public function checkVendor(): bool
    {
        $vendor = Auth::user()->vendor;
    }
}
