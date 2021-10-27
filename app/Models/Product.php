<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model {
    /**
     * protected var
     * Table Name
     */
    protected $table = 'products';
    protected $hidden = ["pivot", "created_at", "updated_at"];
}
