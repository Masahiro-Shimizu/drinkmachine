<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Sale extends Model
{
    //テーブル名
    protected $table = 'sales';

    //可変項目
    protected $fillable = 
    [
        'produuct_id',
    ];
    public function products()
    {
        return $this->belongsTo('App\Models\product');
    }
}