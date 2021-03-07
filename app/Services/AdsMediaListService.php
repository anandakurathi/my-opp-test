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
                return $query->select('id', 'name as provider_name');
            },
            'media_type' => function ($query) {
                return $query->select('id', 'name as media_name');
            }
        ])->orderBy('id', 'DESC')->paginate(5);
    }
}
