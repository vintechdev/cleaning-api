<?php

namespace App\Repository\Eloquent;

use App\StripeUserMetadata;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class StripeUserMetadataRepository
 * @package App\Repository\Eloquent
 */
class StripeUserMetadataRepository extends AbstractBaseRepository
{
    /**
     * @return string
     */
    protected function getModelClass(): string
    {
        return StripeUserMetadata::class;
    }

    /**
     * @param $userId
     * @return StripeUserMetadata
     */
    public function findByUserId($userId) : ?StripeUserMetadata
    {
        /** @var Collection $collection */
        $collection = StripeUserMetadata::where('user_id', $userId)->get();
        return $collection->first();
    }
}