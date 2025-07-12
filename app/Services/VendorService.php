<?php

namespace App\Services;

use App\Http\Requests\StoreVendorRequest;
use App\Models\Vendor;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class VendorService
{
    public function createVendor(StoreVendorRequest $request): Vendor
    {
        return DB::transaction(fn () =>
            Vendor::create([
                'user_id' => Auth::id(),
                'name' => $request->name,
                'company_name' => $request->company_name,
            ])
        , 3);
    }
}
