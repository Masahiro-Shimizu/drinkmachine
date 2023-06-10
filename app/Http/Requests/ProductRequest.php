<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'keyword' => 'string',
            'from_price' => 'integer',
            'to_price' => 'integer',
            'from_stock' => 'integer',
            'to_stock' => 'integer',
            'product_name' => ['required','string', 'max:255'],
            'price' => ['required','integer',  'min:0'],
            'stock' => ['required','integer',  'min:0'],
            'comment' => ['max:200'],
            'company_id' => ['required','exists:companies,id'],
            'img_path' => [
                'file', // ファイルがアップロードされている
                'image', // 画像ファイルである
                'mimes:jpeg,jpg,png', // 形式はjpegかpng
                'dimensions:min_width=50,min_height=50,max_width=1000,max_height=1000', // 50*50 ~ 1000*1000 まで
              ]

        ];
    }
}