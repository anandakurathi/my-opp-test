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
        $req = [
            'name' => 'required',
            'provider' => 'required|exists:providers,id'
        ];

        if (Request::has('image_file')) {
            $req['image_file'] = 'required|mimes:'.$this->mimeTypesAllowed($request);
        }

        if (Request::has('video_file')) {
            $req['video_file'] = 'required|mimes:'.$this->mimeTypesAllowed($request);
        }

        return $req;
    }

    /**
     * Get all the mime Types allowed as per Provider and category
     * @param  Request  $request
     * @return string
     */
    private function mimeTypesAllowed(Request $request)
    {
        $category = $request->image_file ? 'image' : 'video';
        $allowedMediaTypes = \App\Models\ProviderSupportedMediaType::where('provider_id', $request->provider)
            ->whereHas(
                'media_type', function ($query) use ($category) {
                return $query->where('category', $category);
            })->with(
                'media_type', function ($query) {
                return $query->select('id', 'name as media_name');
            })->get();
        $mediaType = [];
        foreach ($allowedMediaTypes as $allowedMediaType) {
            if ($allowedMediaType->media_type) {
                $mediaType[] = $allowedMediaType->media_type->media_name;
            }
        }
        return implode(',', $mediaType);
    }
}
