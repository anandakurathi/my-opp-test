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
            ->whereHas('media_type', function ($query) use ($mediaType) {
                $query->where('name', $mediaType);
            })
            ->with([
                'media_type' => function ($query) {
                    return $query->select('id', 'name as media_name');
                },

                'provider' => function ($query) {
                    return $query->select('id', 'name as provider_name');
                },

                'restrictions' => function ($query) {
                    return $query->select('id', 'provider_supported_media_type_id', 'key_name', 'key_value');
                }
            ])->first();
    }

    /**
     * Return name of method to check gateway
     * @param $keyName
     * @return string|null
     */
    private function getCheckMethod($keyName)
    {
        $methodName = $keyName.'_check';
        if (!method_exists($this, $methodName)) {
            return null;
        }
        return $methodName;
    }

    /**
     * Validate the rules by calling dynamic methods
     * @param  UploadMediaRequest  $request
     * @param $rules
     * @return array|false
     */
    public function validateRules(UploadMediaRequest $request, $rules)
    {
        $response = $this->baseErrorResponse();
        foreach ($rules->restrictions as $rule) {
            $methodName = $this->getCheckMethod($rule->key_name);
            if ($methodName === null) {
                $response['message'] = 'Media rules not found';
                return $response;
            }

            $condition = $this->$methodName($request, $rule->key_value);
            if ($condition['error']) {
                return $condition;
            }
        }
        return false;
    }

    public function aspect_ratio_check($request, $ratio)
    {
        $response = $this->baseErrorResponse();
        list($width, $height) = getimagesize($_FILES["image_file"]['tmp_name']);
        if (AspectRatio::genericRatioCalculator($ratio) !==
            AspectRatio::HeightWidthToAspactRatio($height, $width)) {
            $response['error'] = true;
            $response['message'] = 'Aspect Ration not matched';
        }
        return $response;
    }

    public function max_duration_check($request, $duration)
    {
        $response = $this->baseErrorResponse();
        $getID3 = new \getID3;
        $file = $getID3->analyze($_FILES["video_file"]['tmp_name']);
        $playtimeSeconds = $file['playtime_seconds'];
        if ($duration < $playtimeSeconds) {
            $response['error'] = true;
            $response['message'] = 'Duration of video is exceeded';
        }
        return $response;
    }

    public function max_size_check($request, $size)
    {
        $response = $this->baseErrorResponse();
        if ($request->image_file) {
            $fileSize = $request->file('image_file')->getSize();
        }
        if ($request->video_file) {
            $fileSize = $request->file('video_file')->getSize();
        }
        if ($size > $fileSize) {
            $response['error'] = true;
            $response['message'] = 'File size is greater than the expected';
        }
        return $response;
    }

    /**
     * @return array
     */
    private function baseErrorResponse(): array
    {
        return ['error' => false, 'message' => null];
    }
}
