<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DefectiveItem extends Model
{
    use HasFactory;

    protected $primaryKey = 'defect_id';

    protected $fillable = [
        'item_id',
        'supplier_id',
        'quantity_defective',
        'defect_reason',
        'defect_date',
        'status',
        'repair_date',
        'disposal_date',
        'reported_by'
    ];

    protected $casts = [
        'defect_date' => 'datetime',
        'repair_date' => 'datetime',
        'disposal_date' => 'datetime'
    ];

    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id', 'item_id');
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }
}
