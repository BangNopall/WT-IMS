<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Product_Masuk extends Model
{
    use Notifiable;

    protected $table = 'product_masuk';

    protected $fillable = ['product_id','customer_id','qty','tanggal'];

    protected $hidden = ['created_at','updated_at'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function discord()
    {
        return $this->belongsTo(Discord::class);
    }
}
