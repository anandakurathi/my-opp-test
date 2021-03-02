<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Restriction
 *
 * @property int $id
 * @property int $provider_supported_media_type_id
 * @property string $key_name
 * @property string $key_value
 * @property string $status
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @property ProviderSupportedMediaType $provider_supported_media_type
 *
 * @package App\Models
 */
class Restriction extends Model
{
	protected $table = 'restrictions';

	protected $casts = [
		'provider_supported_media_type_id' => 'int'
	];

	protected $fillable = [
		'provider_supported_media_type_id',
		'key_name',
		'key_value',
		'status'
	];

	public function provider_supported_media_type()
	{
		return $this->belongsTo(ProviderSupportedMediaType::class);
	}
}
