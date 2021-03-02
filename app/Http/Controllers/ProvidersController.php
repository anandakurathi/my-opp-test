<?php

namespace App\Http\Controllers;

use App\Models\Provider;
use App\Services\ProvidersListService;
use Illuminate\Http\Request;

class ProvidersController extends Controller
{
    public $providersList;

    public function __construct(ProvidersListService $providersListService)
    {
        $this->providersList = $providersListService;
    }

    public function index()
    {
        $provider = $this->providersList->getProviderListWithRules();
        return response(
            [
                'message' => 'Providers retrieved successfully',
                'provider' => $provider
            ], 200
        );
    }
}
