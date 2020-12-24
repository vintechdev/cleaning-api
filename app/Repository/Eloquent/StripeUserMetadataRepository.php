<?php

namespace App\Repository\Eloquent;

use App\StripeUserMetadata;
use App\User;
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
        if (!$collection->count()) {
            return null;
        }
        return $collection->first();
    }

    /**
     * @param User $user
     * @return bool
     */
    public function hasUserSavedCard(User $user): bool
    {
        $metadata = $this->findByUserId($user->getId());

        if (!$metadata || !$metadata->stripe_payment_method_id) {
            return false;
        }

        return true;
    }
}