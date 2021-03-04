<?php

namespace App\Http\Controllers;

use App\Http\Requests\UploadMediaRequest;
use App\Services\UploadMediaService;
use Illuminate\Http\Request;

class UploadMediaController extends Controller
{
    Protected $uploadMedia;

    public function __construct(UploadMediaService $uploadMediaService)
    {
        $this->uploadMedia = $uploadMediaService;
    }

    public function index(UploadMediaRequest $request)
    {
        return 'ok';
    }

}
