<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Model;
use Laravel\Lumen\Auth\Authorizable;
use Tymon\JWTAuth\Contracts\JWTSubject;


class User extends Model implements JWTSubject, AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password',
    ];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
	}

    /**
     * Products purchased by a user
     *
     * @return Illuminate\Database\Eloquent\Concerns\HasRelationships::belongsToMany
     */
    public function products() {
        return $this->belongsToMany(
            Product::class, // Target model
            "purchased",    // Pivot Table name
            "user_id",      // Foreign key on purchased table...
            "product_sku",  // Foreign key on purchased table...
            "id",           // Local key on user table...
            "sku"           // Local key on products table...
        );
    }

    /**
     * Purchase record for a user
     *
     * @return Illuminate\Database\Eloquent\Concerns\HasRelationships::hasMany
     */
    public function purchases() {
        return $this->hasMany(
            Purchased::class, // Target model
            "user_id",      // Foreign key on purchased table...
            "id"           // Local key on user table
        );
    }
}
