<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Item extends Model
{
    use SoftDeletes;
    
    protected $table = 'items';
    protected $primaryKey = 'item_id';
    public $timestamps = false;
    protected $fillable = [
        'item_name', 'item_code', 'category', 'description', 'quantity', 'unit', 'cost_price', 'location', 'supplier_id', 'date_received', 'deleted_by'
    ];

    protected static function booted()
    {
        static::deleting(function ($item) {
            if (auth()->check()) {
                $item->deleted_by = auth()->id();
                $item->save();
            }
        });
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id', 'supplier_id');
    }
}
