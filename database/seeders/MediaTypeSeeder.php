<?php

namespace Database\Seeders;

use App\Models\MediaType;
use Illuminate\Database\Seeder;

class MediaTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $imageTypes = [
            [
                'name' => '.jpg',
                'category' => 'image',
                'status' => 'A'
            ],
            [
                'name' => '.gif',
                'category' => 'image',
                'status' => 'A'
            ],
            [
                'name' => '.mp4',
                'category' => 'video',
                'status' => 'A'
            ],
            [
                'name' => '.mp3',
                'category' => 'video',
                'status' => 'A'
            ],
            [
                'name' => '.mov',
                'category' => 'video',
                'status' => 'A'
            ]
        ];

        foreach ($imageTypes as $imageType) {
            MediaType::create($imageType);
        }
    }
}
