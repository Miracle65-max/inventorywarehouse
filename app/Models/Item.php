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
        static::creating(function ($item) {
            // Auto-generate item code if not provided
            if (empty($item->item_code)) {
                $item->item_code = self::generateUniqueItemCode($item->category);
            }
        });

        static::deleting(function ($item) {
            if (auth()->check()) {
                $item->deleted_by = auth()->id();
                $item->save();
            }
        });
    }

    /**
     * Generate a unique item code based on category
     */
    public static function generateUniqueItemCode($category = 'other')
    {
        $prefix = strtoupper(substr($category, 0, 3));
        $year = date('Y');
        $month = date('m');
        
        // Get the last item code for this category and month
        $lastItem = self::where('item_code', 'like', $prefix . $year . $month . '%')
            ->orderBy('item_code', 'desc')
            ->first();
        
        if ($lastItem) {
            // Extract the sequence number and increment
            $lastSequence = (int) substr($lastItem->item_code, -4);
            $newSequence = $lastSequence + 1;
        } else {
            $newSequence = 1;
        }
        
        // Format: CAT-YYYYMM-0001 (e.g., ELE-202412-0001)
        return $prefix . '-' . $year . $month . '-' . str_pad($newSequence, 4, '0', STR_PAD_LEFT);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id', 'supplier_id');
    }
}
