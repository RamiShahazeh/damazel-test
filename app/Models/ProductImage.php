<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model
{
    use HasFactory;

        /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    public $timestamps = false;

    protected $fillable = [
        'image_url',
        'sort'
    ];


    // Relationships

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

}
