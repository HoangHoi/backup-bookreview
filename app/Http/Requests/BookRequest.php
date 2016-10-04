<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class BookRequest extends Request
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
            'title' => 'required',
            'category_id' => 'required|exists:categories,id',
            'book_image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'published_at' => 'date_format:' . config('common.publish_date_format'),
        ];
    }
}
