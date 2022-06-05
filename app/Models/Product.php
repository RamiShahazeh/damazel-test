<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

        /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'price',
        'description',
        'main_image',
    ];

    protected $casts = [
        'created_at' => 'date:Y-m-d h:m'
    ];

    // Relationships

    public function images()
    {
        return $this->HasMany(ProductImage::class);
    }

}
