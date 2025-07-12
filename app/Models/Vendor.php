<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Vendor extends Model
{
    Use HasFactory;

    protected $fillable= [

       'user_id',
        'name',
        'company_name'   
    ];
 
       public function user()
    {
        return $this->belongsTo(User::class);
    }
}
