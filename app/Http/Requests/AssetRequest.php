<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AssetRequest extends FormRequest
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
            'name' => 'required|max:255',
            'location_id' => 'required|integer',
            'asset_category_id' => 'required|integer',
        ];
    }

    public function attributes()
    {
        return [
            'name' => 'Asset Name',
            'location_id' => 'Asset Location',
            'asset_category_id' => 'Asset Category',
        ];
    }
}
