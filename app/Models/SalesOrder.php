<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SalesOrder extends Model
{
    use SoftDeletes;
    
    protected $primaryKey = 'order_id';
    public $incrementing = true;
    protected $keyType = 'int';
    
    protected $fillable = [
        'order_number',
        'customer_name',
        'customer_email',
        'customer_phone',
        'customer_address',
        'order_date',
        'pickup_date',
        'total_amount',
        'notes',
        'status',
        'created_by'
    ];

    protected $casts = [
        'order_date' => 'datetime',
        'pickup_date' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    // If your table name is not the plural of the model name, uncomment below:
    // protected $table = 'sales_orders';

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'order_id', 'order_id');
    }

    public function items()
    {
        return $this->belongsToMany(Item::class, 'order_items', 'order_id', 'item_id', 'order_id', 'item_id');
    }
}
