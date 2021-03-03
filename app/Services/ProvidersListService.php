<?php


namespace App\Services;


use App\Models\Provider;

class ProvidersListService
{
    /**
     * Get Provider List With Rules
     * @return mixed
     */
    public function getProviderListWithRules()
    {
        return Provider::select('id', 'name')->with([
            'provider_supported_media_types' => function ($query) {

                $query->select('id', 'media_type_id', 'provider_id')->with([

                    'media_type' => function ($query) {
                        $query->select('id', 'name');
                        return $query;
                    },

                    'restrictions' => function ($query) {
                        $query->select('id', 'provider_supported_media_type_id', 'key_name', 'key_value');
                        return $query;
                    }

                ]);

            },
        ])->get();
    }
}
