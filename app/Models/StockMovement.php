<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockMovement extends Model
{
    protected $table = 'stock_movements';
    protected $fillable = [
        'item_id', 'movement_type', 'quantity_changed', 'new_total_quantity', 'user_id', 'remarks'
    ];
    public $timestamps = false;

    public function item() { return $this->belongsTo(Item::class, 'item_id'); }
    public function user() { return $this->belongsTo(User::class, 'user_id'); }
}
