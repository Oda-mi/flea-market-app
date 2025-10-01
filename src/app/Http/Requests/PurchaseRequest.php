<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PurchaseRequest extends FormRequest
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
            'payment_method' => 'required|in:convenience,credit',
            'postal_code' => 'required',
            'address' => 'required',
        ];
    }

    public function messages ()
    {
        return [
        'payment_method.required' => '支払い方法を選択してください',
        'payment_method.in' => '正しい支払い方法を選択してください',
        'postal_code.required' => '配送先の郵便番号を登録してください',
        'address.required' => '配送先の住所を登録してください',
        ];
    }
}
