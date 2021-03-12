<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Emadadly\LaravelUuid\Uuids;

/**
 * Class Providermetadatum
 * @package App
 */
class Providermetadatum extends Model
{
    use uuids;
    protected $table = 'provider_metadata';

    protected $fillable = ['id'];
    public $incrementing = false;

    /**
     * @param $providerId
     * @return $this | null
     */
    public static function findByProviderId($providerId)
    {
        /** @var Builder $metadata */
        $metadata = self::where(['provider_id' => $providerId]);
        if (!$metadata->count()) {
            return null;
        }

        return $metadata->first();
    }

    /**
     * @param bool $verify
     * @return $this
     */
    public function setVerified(bool $verify = true)
    {
      $this->verify = $verify;
      return $this;
    }

    /**
     * @return bool
     */
    public function isVerified(): bool
    {
      return (bool)$this->verify;
    }

    /**
     * @param float $serviceFeePercentage
     * @return $this
     */
    public function setServiceFeePercentage(float $serviceFeePercentage)
    {
      $this->service_fee_percentage = $serviceFeePercentage;
      return $this;
    }

    /**
    * @return float|null
    */
    public function getServiceFeePercentage(): ?float
    {
      return $this->service_fee_percentage;
    }
}
