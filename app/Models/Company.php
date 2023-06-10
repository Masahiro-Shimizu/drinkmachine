<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    //挿入できるようにカラムを宣言
  protected $fillable = ['company_name', 'street_address', 'representative_name'];

    /*  //テーブルを指定
     protected  $table='companies';

    
 
     public function products(){
         return $this->hasMany(Product::class);
     }
*/

 //メーカー絞り込み
 public function getCompany()
 {
     $companies = Company::pluck('company_name', 'id');
        return $companies;
   
 }

 /*    
 //カテゴリー一覧を取得
    public function getLists() {
        $companies = Company::orderBy('id' , 'asc') -> pluck('company_name' , 'id');
        return $companies;
        }
        */
}