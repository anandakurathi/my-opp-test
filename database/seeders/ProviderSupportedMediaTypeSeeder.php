<?php

namespace Database\Seeders;

use App\Models\MediaType;
use App\Models\Provider;
use App\Models\ProviderSupportedMediaType;
use App\Models\Restriction;
use Illuminate\Database\Seeder;

class ProviderSupportedMediaTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $rules = self::providerMediaRules();
        foreach ($rules as $key => $rule) {
            $provider = Provider::where(['name' => $key, 'status' => 'A'])->first();
            $mediaExtinction = array_keys($rule);
            $mediaTypes = MediaType::whereIn('name', $mediaExtinction)->get();
            foreach ($mediaTypes as $mediaType) {
                // Insert data relation in Provider Support Media Types
                $providerMediaTypes = new ProviderSupportedMediaType;
                $providerMediaTypes->media_type_id = $mediaType->id;
                $providerMediaTypes->provider_id = $provider->id;
                $providerMediaTypes->status = 'A';
                $providerMediaTypes->save();
                $providerMediaTypeId = $providerMediaTypes->id;

                $restriction = self::prepareRestriction(
                    $providerMediaTypeId,
                    $rules[$provider->name][$mediaType->name]
                );
                Restriction::insert($restriction);
            }
        }
    }

    /**
     * Prepare the data schema
     * @param  int  $providerMediaTypeId
     * @param  array  $rules
     * @return array[]
     */
    public static function prepareRestriction(
        int $providerMediaTypeId,
        $rules = []
    ) {
        $restriction = [];
        foreach ($rules as $key => $rule) {
            $restriction[] = [
                'provider_supported_media_type_id' => $providerMediaTypeId,
                'key_name' => $key,
                'key_value' => $rule,
                'status' => 'A',
                'created_at' => now(),
                'updated_at' => now()
            ];
        }
        return $restriction;
    }

    /**
     * Master data rules
     * @return array
     */
    public static function providerMediaRules()
    {
        return [
            'Google' => [
                'jpg' => [
                    'aspect_ratio' => '4:3',
                    'max_size' => 2, // in MB always
                ],
                'mp4' => [
                    'max_duration' => '60', // secounds
                ],
                'mp3' => [
                    'max_duration' => '30', // secounds
                    'max_size' => 5,
                ]
            ],
            'Snapchat' => [
                'jpg' => [
                    'aspect_ratio' => '16:9',
                    'max_size' => 5,
                ],
                'gif' => [
                    'aspect_ratio' => '16:9',
                    'max_size' => 5,
                ],
                'mp4' => [
                    'max_duration' => '300', // secounds
                    'max_size' => 50,
                    'preview' => true
                ],
                'mov' => [
                    'max_duration' => '300', // secounds
                    'max_size' => 50,
                    'preview' => true
                ]
            ],
        ];
    }

}
