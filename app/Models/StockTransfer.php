<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class StockTransfer extends Model
{
    use HasFactory;
    protected $table = 'stock_transfers';
    protected $fillable = [
        'product_id',
        'from_shop_id',
        'to_shop_id',
        'qty',
        'remark',
        'status',
        'request_by',
        'request_by_type'
    ];
    protected $appends = [
        'created_date'
    ];
    public function product()
    {
        return $this->hasOne(Product::class, 'id', 'product_id');
    }
    public function shop()
    {
        return $this->hasOne(Shop::class, 'id', 'from_shop_id');
    }
    public function shopTo()
    {
        return $this->hasOne(Shop::class, 'id', 'to_shop_id');
    }
    public function getCreatedDateAttribute()
    {
        return $this->created_at ? Carbon::parse($this->created_at)->format('d/M/Y h:i A') : null;
    }
    public function user()
    {
        return $this->hasOne(User::class, 'id', 'request_by');
    }
    public function barber()
    {
        return $this->hasOne(Barber::class, 'id', 'request_by');
    }
}
