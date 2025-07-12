<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    //
        use HasFactory;

    protected $fillable = [
        'vendor_id',
        'name',
        'description',
        'price',
    ];

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }
}
