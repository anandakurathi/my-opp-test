<?php

namespace App\Http\Controllers;

use App\Services\AdsMediaListService;
use Illuminate\Http\Request;

class AdsMediaListController extends Controller
{
    public $adsMedia;

    public function __construct(AdsMediaListService $adsMediaListService)
    {
        $this->adsMedia = $adsMediaListService;
    }

    public function index()
    {
        $ads = $this->adsMedia->getAdsList();
        return response(
            [
                'message' => 'Ads retrieved successfully',
                'provider' => $ads
            ], 200
        );
    }
}
