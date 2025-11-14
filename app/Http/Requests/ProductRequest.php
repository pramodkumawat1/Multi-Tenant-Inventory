<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class ProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // $user = $this->user();
        // $product = $this->route('product');
        // if ($this->isMethod('post')) {
        //     return true;
        // }
        // if ($this->isMethod('put') || $this->isMethod('patch')) {
        //     return $user->can('update', $product);
        // }
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $productParam = $this->route('product');
        $productId = is_object($productParam) ? $productParam->id : $productParam;
        return [
            'name' => 'required|string|max:255|unique:products,name,' . $productId,
            'sku' => 'required|string|unique:products,sku,' . $productId,
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $errors = $validator->errors();

        if ($this->expectsJson()) {
            throw new HttpResponseException(
                response()->json(['status' => false,
                    'message' => $errors->first()
                ], 422)
            );
        }

        parent::failedValidation($validator);
    }
}
