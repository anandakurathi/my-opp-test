<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class UploadMediaRequest extends FormRequest
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
    public function rules(Request $request)
    {
        return [
            'name' => 'required',
            'provider' => 'required|exists:providers,id',
            'image_file' => [
                'required',
                'mimes:'.$this->mimeTypesAllowed($request)
            ]
        ];
    }

    private function mimeTypesAllowed(Request $request)
    {
        $allowedMediaTypes = \App\Models\ProviderSupportedMediaType::where('provider_id', $request->provider)
            ->with([
                'media_type' => function ($query) {
                    $query->select('id', 'name as media_name');
                    return $query;
                }
            ])->get();
        $mediaType = [];
        foreach ($allowedMediaTypes as $allowedMediaType) {
            $mediaType[] = $allowedMediaType->media_type->media_name;
        }
        return implode(',', $mediaType);
    }
}
