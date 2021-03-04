<?php

namespace App\Http\Controllers;

use App\Http\Requests\UploadMediaRequest;
use App\Models\AdsMedia;
use App\Services\UploadMediaService;
use FFMpeg\FFMpeg;
use FFMpeg\FFProbe;
use Illuminate\Support\Facades\Storage;

class UploadMediaController extends Controller
{
    const STORAGE_DIRECTORY = 'public/';
    protected $uploadMedia;

    public function __construct(UploadMediaService $uploadMediaService)
    {
        $this->uploadMedia = $uploadMediaService;
    }

    public function index(UploadMediaRequest $request)
    {
        if (!$request->hasFile('image_file')) {
            return response()->json(
                [
                    "message" => "Could not find a media file"
                ],
                422
            );
        }

        $getID3 = new \getID3;
        $file = $getID3->analyze($_FILES["image_file"]['tmp_name']);
        $playtime_seconds = $file['playtime_seconds'];

        dd($playtime_seconds);
        $file = $request->image_file->getClientOriginalName();
        $fileName = pathinfo($file, PATHINFO_FILENAME);
        $fieExtension = pathinfo($file, PATHINFO_EXTENSION);
        $providerMediaRules = $this->uploadMedia->getRulesByProviderAndMediaTYpe($request, $fieExtension);

//        dd($providerMediaRules->toArray());

        $currentFileName = time().'_'.$fileName.'.'.$fieExtension;
        $path = $providerMediaRules->provider->provider_name.'/'.date('d-m-Y').'/';
        try {
            $request->image_file->storeAs(self::STORAGE_DIRECTORY.$path, $currentFileName);
        } catch (\Exception $exception) {
            return response()->json(
                [
                    "message" => 'Could not upload image:'.$exception->getMessage()
                ],
                500
            );
        }

        $adsMedia = $this->uploadMedia->insertAdsMedia(
            $request,
            $path.$currentFileName,
            $providerMediaRules->media_type_id,
            $providerMediaRules->provider_id
        );
        if ($adsMedia) {
            Storage::delete(self::STORAGE_DIRECTORY.$path.$currentFileName);
            return response()->json(
                [
                    'message' => 'Something went wrong in ad upload',
                ], 500
            );
        }
        $adsMediaData = $adsMedia->find($adsMedia->id);

        return response()->json(
            [
                'message' => 'Ads uploaded successfully',
                'datat' => $adsMediaData
            ], 200
        );
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


}
