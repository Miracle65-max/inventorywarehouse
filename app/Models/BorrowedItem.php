<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BorrowedItem extends Model
{
    use HasFactory;

    protected $table = 'borrowed_items_new';
    protected $primaryKey = 'borrowed_id';
    public $timestamps = false;

    protected $fillable = [
        'item_id',
        'quantity',
        'borrower_name',
        'source_warehouse',
        'expected_return_date',
        'borrowed_date',
        'returned_date',
        'status'
    ];

    protected $casts = [
        'borrowed_date' => 'datetime',
        'expected_return_date' => 'datetime',
        'returned_date' => 'datetime'
    ];

    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id', 'item_id');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'borrowed');
    }
}
