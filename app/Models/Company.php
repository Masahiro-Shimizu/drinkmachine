<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\support\Facades\DB;

class Company extends Model
{
    //テーブル名
    protected $table = 'companies';

    //可変項目
    protected $fillable = 
    [
        'company_name',
        'street_address',
        'responsentive_name',
    ];

    public function products(){
        return $this->hasMany("App\Models\Product");
    }

    /**
     * クエリビルダでDBからcompanyデータを取得
     * 
     * @return $company
     */
    public function companyList()
    {
        $company = DB::table('companies')
        ->select('id','company_name',)
        ->orderBy('id','asc')
        ->get();

        return $company;
    }
}
