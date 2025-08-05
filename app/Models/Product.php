<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'nama',
        'harga',
        'kategori',
        'qty',
        // 'category_id',
        // 'created_at',
        // 'updated_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @return array<string, string>
     */

    protected $casts = [
        'harga' => 'decimal:2',
        'qty' => 'integer',
    ];

    protected function getStockStatus(): array
    {
        if ($this->stock > 0) {
            return [
                'status' => 'available', 
                'message' => 'In Stock',
                'class' => 'text-green-500 bg-green-100',
            ];
        }

        if ($this->stock <= 2) {
            return [
                'status' => 'Low Stock', 
                'class' => 'text-yellow-500 bg-yellow-100',
                'message' => 'Low Stock',
            ];
        }

        return [
            'status' => 'out_of_stock', 
            'message' => 'Out of Stock',
            'class' => 'text-red-500 bg-red-100',
        ];
    }

    public function getFormatedPrice(): string
    {
        return number_format($this->harga, 0, ',', '.');
    }
}
