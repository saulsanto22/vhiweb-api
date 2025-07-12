<?php

namespace App\Http\Controllers\API;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use Illuminate\Support\Facades\Auth;
use App\Services\ProductService;
use App\Http\Resources\ProductResource;
use Illuminate\Support\Facades\DB;


class ProductController extends Controller
{
    public function __construct(protected ProductService $service) {}

    public function index(): JsonResponse
    {
        $products = $this->service->getUserProducts();
        return response()->success('List of products', ProductResource::collection($products));
    }

    public function store(StoreProductRequest $request): JsonResponse
    {
        $product = $this->service->createProduct($request);
        return response()->success('Product created successfully', new ProductResource($product), 201);
    }

    public function update(UpdateProductRequest $request, Product $product): JsonResponse
    {
        if ($product->vendor->user_id !== Auth::id()) {
            return response()->error('Unauthorized', 403);
        }

        $product = $this->service->updateProduct($request, $product);
        return response()->success('Product updated', new ProductResource($product));
    }

    public function destroy(Product $product): JsonResponse
    {
        if ($product->vendor->user_id !== Auth::id()) {
            return response()->error('Unauthorized', 403);
        }

        $this->service->deleteProduct($product);
        return response()->success('Product deleted');
    }
}
