<?php


namespace App\Services;


use App\Models\AdsMedia;

class AdsMediaListService
{
    /**
     * Get Ads List in decending order with pagination limit 5
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getAdsList()
    {
        return AdsMedia::with([
            'provider' => function ($query) {
                $query->select('name as provider_name');
                return $query;
            },
            'media_type' => function ($query) {
                $query->select('name as media_name');
                return $query;
            }
        ])->orderBy('id', 'DESC')->paginate(5);
    }
}
