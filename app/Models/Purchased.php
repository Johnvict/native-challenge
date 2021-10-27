<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Purchased extends Model {
    /**
     * protected var
     * Table Name
     */
    protected $table = 'purchased';

    protected $fillable = ["user_id", "product_sku"];

}
