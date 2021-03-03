<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Provider
 *
 * @property int $id
 * @property string $name
 * @property string $status
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @property Collection|ProviderSupportedMediaType[] $provider_supported_media_types
 * @property Collection|AdsMedia[] $ads_media
 *
 * @package App\Models
 */
class Provider extends Model
{
	protected $table = 'providers';

	protected $fillable = [
		'name',
		'status'
	];

	public function provider_supported_media_types()
	{
		return $this->hasMany(ProviderSupportedMediaType::class);
	}

    public function ads_media()
    {
        return $this->hasMany(AdsMedia::class);
    }
}
