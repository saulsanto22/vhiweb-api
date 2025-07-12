<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\StoreVendorRequest;
use App\Http\Resources\VendorResource;
use App\Models\Vendor;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Services\VendorService;

class VendorController extends Controller
{
    public function __construct(protected VendorService $service) {}

    public function show()
    {
        $vendor = Auth::user()->vendor;

        return $vendor
            ? response()->success('Vendor details', new VendorResource($vendor))
            : response()->error('Vendor not found', 404);
    }

    public function store(StoreVendorRequest $request)
    {
        if (Auth::user()->vendor) {
            return response()->error('You already have a vendor.', 400);
        }

        $vendor = $this->service->createVendor($request);
        return response()->success('Vendor registered successfully', new VendorResource($vendor), 201);
    }
}
