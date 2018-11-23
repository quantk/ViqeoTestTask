<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\UploadedFile;

/**
 * Class UploadImageRequest
 * @package App\Http\Requests
 * @property UploadedFile $image
 * @property integer|null $width
 * @property integer|null $height
 */
class UploadImageRequest extends FormRequest
{
    public const MAX_SIZE_MB = 10;
    public const MAX_WIDTH = 2000;
    public const MAX_HEIGHT = 2000;

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
            'image' => sprintf('required|file|mimes:jpeg,png|max:%d|dimensions:max_width=%d,max_height=%d',
                self::MAX_SIZE_MB * 1024, self::MAX_WIDTH, self::MAX_HEIGHT
            ),
            'width' => 'required_without:height|integer',
            'height' => 'required_without:width|integer'
        ];
    }
}
