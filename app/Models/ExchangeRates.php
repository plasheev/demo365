<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExchangeRates extends Model
{
    use HasFactory;
    
    protected $table = 'exchange_rates';
    protected $primaryKey = 'id';
    public $incrementing = false;
    
}
