<?php


namespace App\Services;


use App\Helpers\AspectRatio;
use App\Http\Requests\UploadMediaRequest;
use App\Models\AdsMedia;

class UploadMediaService
{
    /**
     * Insert a row to AdsMedia DB
     * @param  UploadMediaRequest  $request
     * @param $path
     * @param $mediaTypeId
     * @param $providerId
     * @return AdsMedia|false
     */
    public function insertAdsMedia(UploadMediaRequest $request, $filePath, $mediaTypeId, $providerId)
    {
        $adsMedia = new AdsMedia;
        $adsMedia->ad_name = $request->name;
        $adsMedia->file_path = $filePath;
        $adsMedia->media_type_id = $mediaTypeId;
        $adsMedia->provider_id = $providerId;
        $adsMedia->status = AdsMedia::ADS_MEDIA_STATUS['Active'];
        $adsMedia->created_at = now();
        $adsMedia->updated_at = now();
        if (!$adsMedia->save()) {
            return false;
        }
        return $adsMedia;
    }

    public function getRulesByProviderAndMediaTYpe(UploadMediaRequest $request, $mediaType)
    {
        return \App\Models\ProviderSupportedMediaType::where('provider_id', $request->provider)
            ->with([
                'media_type' => function ($query) use ($mediaType) {
                    $query->select('id', 'name as media_name')
                        ->where('name', $mediaType);
                    return $query;
                },

                'provider' => function ($query) {
                    $query->select('id', 'name as provider_name');
                    return $query;
                },

                'restrictions' => function ($query) {
                    $query->select('id', 'provider_supported_media_type_id', 'key_name', 'key_value');
                    return $query;
                }
            ])->first();
    }

    public function validateRules(UploadMediaRequest $request, $rules)
    {
        foreach ($rules as $rule) {
            $methodName = $rule->key_name;
            $condition = $this->$methodName.'_check('.$request.','.$rule->key_value.')';
            if ($condition['error']) {
                return $condition['message'];
            }
        }
        return false;
    }

    public function aspect_ratio_check($request, $ratio)
    {
        $response = ['error'=>false, 'message' => null];
        list($width, $height) = getimagesize($_FILES["image_file"]['tmp_name']);
        if (AspectRatio::genericRatioCalculator($ratio) !==
            AspectRatio::HeightWidthToAspactRatio($height, $width)) {
            $response['error'] = true;
            $response['message'] = 'Aspect Ration not matched';
        }
        return $response;
    }

    public function max_duration_check($duration)
    {
        $response = ['error'=>false, 'message' => null];



        return $response;
    }

    public function max_size_check($size)
    {
        $response = ['error'=>false, 'message' => null];

        return $response;
    }
}
