<?php

namespace App\Http\Controllers;

use App\Http\Requests\UploadMediaRequest;
use App\Services\UploadMediaService;
use Illuminate\Support\Facades\Storage;

class UploadMediaController extends Controller
{
    const STORAGE_DIRECTORY = 'public/';
    protected $uploadMedia;

    public function __construct(UploadMediaService $uploadMediaService)
    {
        $this->uploadMedia = $uploadMediaService;
    }

    /**
     * Upload Ad and info to application
     * @param  UploadMediaRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(UploadMediaRequest $request)
    {
        if ($request->image_file) {
            $file = $request->image_file->getClientOriginalName();
        } else {
            $file = $request->video_file->getClientOriginalName();
        }

        $fileName = pathinfo($file, PATHINFO_FILENAME);
        $fieExtension = pathinfo($file, PATHINFO_EXTENSION);
        $providerMediaRules = $this->uploadMedia->getRulesByProviderAndMediaTYpe($request, $fieExtension);

        $validateRules = $this->uploadMedia->validateRules($request, $providerMediaRules);
        if ($validateRules && array_key_exists('error', $validateRules)) {
            return $this->responseData(422, $validateRules['message']);
        }

        $currentFileName = time().'_'.$fileName.'.'.$fieExtension;
        $path = $providerMediaRules->provider->provider_name.'/'.date('d-m-Y').'/';
        try {
            if ($request->image_file) {
                $request->image_file->storeAs(self::STORAGE_DIRECTORY.$path, $currentFileName);
            } else {
                $request->video_file->storeAs(self::STORAGE_DIRECTORY.$path, $currentFileName);
            }
        } catch (\Exception $exception) {
            return $this->responseData(500, 'Could not upload image:'.$exception->getMessage());
        }

        $adsMedia = $this->uploadMedia->insertAdsMedia(
            $request,
            $path.$currentFileName,
            $providerMediaRules->media_type_id,
            $providerMediaRules->provider_id
        );
        if (!$adsMedia) {
            Storage::delete(self::STORAGE_DIRECTORY.$path.$currentFileName);
            return $this->responseData(500, 'Something went wrong in ad upload');
        }
        $adsMediaData = $adsMedia->find($adsMedia->id);

        return $this->responseData(200, 'Ads uploaded successfully', $adsMediaData);
    }

    /**
     * Clean File Name
     * Removes all special characters from a string
     * @param $string
     * @return string|string[]|null
     */
    public function cleanFileName($string)
    {
        // Replaces all spaces with hyphens.
        $string = str_replace(' ', '-', $string);
        // Removes special chars.
        $string = preg_replace('/[^A-Za-z0-9\-]/', '', $string);
        // Replaces multiple hyphens with single one.
        return preg_replace('/-+/', '-', $string);
    }

    /**
     * Prepares the response
     * @param  int  $code
     * @param  string  $message
     * @param  array  $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function responseData($code = 200, $message = '', $data = [])
    {
        return response()->json(
            [
                'message' => $message,
                'datat' => $data
            ], $code
        );
    }


}
