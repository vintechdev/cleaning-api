<?php

namespace App\Repository;

use App\Booking;
use App\Repository\Eloquent\AbstractBaseRepository;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

class BookingSummaryRepository extends AbstractBaseRepository
{
    /**
     * @return string
     */
    protected function getModelClass() : string {
        return Booking::class;
    }

    public function getNewBookings(array $filters = []) : Collection
    {
        $fromDate = Arr::get($filters, 'from') ?
            Carbon::parse(Arr::get($filters, 'from'))->format('Y-m-d 00:00:00'):
         Carbon::now()->firstOfMonth()->format('Y-m-d 00:00:00');

        return  $this->getModel()->newQuery()->where('created_at', '>=', $fromDate)->get();
    }
}
