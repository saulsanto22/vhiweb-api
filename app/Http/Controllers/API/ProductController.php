<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Services\ProductService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function __construct(
        protected ProductService $service
    ) {}

    public function index()
    {
        $products = $this->service->getUserProducts();
        return ProductResource::collection($products);
    }

    public function store(StoreProductRequest $request)
    {
        try {
            $checkVendor = $this->service->checkVendor();

            if (!$vendor) {
                abort(404, 'You do not have a registered vendor yet.');
            }

            $product = $this->service->createProduct($request);
            return response()->success('Product created successfully', new ProductResource($product), 201);
        } catch (\Throwable $th) {
            return response()->error('Terjadi kesalahan', 500, $e->getMessage());
        }
    }

    public function update(UpdateProductRequest $request, $id)
    {
        try {
            $product = $this->service->productFindOrFail($id);

            if (!$this->service->authorizeUser($product)) {
                return response()->error('Unauthorized', 403);
            }

            $updatedProduct = $this->service->updateProduct($request, $product);
            return response()->success('Product updated', new ProductResource($updatedProduct));
        } catch (ModelNotFoundException $e) {
            return response()->error('Data tidak ditemukan', 404);
        } catch (\Throwable $e) {
            return response()->error('Terjadi kesalahan', 500, $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $product = $this->service->productFindOrFail($id);

            if (!$this->service->authorizeUser($product)) {
                return response()->error('Unauthorized', 403);
            }
            $this->service->deleteProduct($product);
            return response()->success('Product deleted');
        } catch (ModelNotFoundException $e) {
            return response()->error('Data tidak ditemukan', 404);
        } catch (\Throwable $e) {
            return response()->error('Terjadi kesalahan', 500, $e->getMessage());
        }
    }
}
