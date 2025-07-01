<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Supplier extends Model
{
    use SoftDeletes;
    
    protected $table = 'suppliers';
    protected $primaryKey = 'supplier_id';
    public $timestamps = true;
    protected $fillable = [
        'supplier_name', 'contact_person', 'contact_number', 'email', 'address', 'notes', 'status'
    ];

    public function items()
    {
        return $this->hasMany(Item::class, 'supplier_id', 'supplier_id');
    }
}
