<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class AdsMedia
 *
 * @property int $id
 * @property int $image_type_id
 * @property int $provider_id
 * @property string $notes
 * @property string $status
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @property MediaType $image_type
 * @property Provider $provider
 *
 * @package App\Models
 */
class AdsMedia extends Model
{
    protected $table = 'ads_media';

    protected $casts = [
        'media_type_id' => 'int',
        'provider_id' => 'int'
    ];

    protected $fillable = [
        'ad_name',
        'media_url',
        'media_type_id',
        'provider_id',
        'status'
    ];

    public function media_type()
    {
        return $this->belongsTo(MediaType::class);
    }

    public function provider()
    {
        return $this->belongsTo(Provider::class);
    }
}
