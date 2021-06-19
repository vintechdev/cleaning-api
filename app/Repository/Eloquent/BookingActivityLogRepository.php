<?php

namespace App\Repository\Eloquent;

use App\Bookingactivitylog;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Arr;

/**
 * Class BookingActivityLogRepository
 * @package App\Repository\Eloquent
 */
class BookingActivityLogRepository extends AbstractBaseRepository
{
    /**
     * @return string
     */
    protected function getModelClass(): string
    {
        return Bookingactivitylog::class;
    }

    /**
     * @param array $filters
     * @return LengthAwarePaginator
     */
    public function getAll(array $filters =[]): LengthAwarePaginator
    {
        $query = Bookingactivitylog::query()->with(['user', 'booking']);

        if (Arr::get($filters, 'user_id')) {
            $query->where('user_id', Arr::get($filters, 'user_id'));
        }

        if (Arr::get($filters, 'booking_id')) {
            $query->where('booking_id', Arr::get($filters, 'booking_id'));
        }

        if (Arr::get($filters, 'status')) {
            $query->where('status', Arr::get($filters, 'status'));
        }

        if (Arr::get($filters, 'is_recurring')) {
            $query->where('is_recurring', Arr::get($filters, 'is_recurring'));
        }

        if (Arr::get($filters, 'booking_date')) {
            $query->where('booking_date', '=', Carbon::parse(Arr::get($filters, 'booking_date'))->format('Y-m-d'));
        }

        if (Arr::get($filters, 'from')) {
            $query->where('created_at', '>=', Carbon::parse(Arr::get($filters, 'from'))->format('Y-m-d 00:00:00'));
        }

        if (Arr::get($filters, 'to')) {
            $query->where('created_at', '<=', Carbon::parse(Arr::get($filters, 'to'))->format('Y-m-d 00:00:00'));
        }

        if (Arr::get($filters, 'postcode')) {
            $query->where('booking_postcode', Arr::get($filters, 'postcode'));
        }

        if (Arr::get($filters, 'action')) {
            $query->where('action', 'like', Arr::get($filters, 'action'));
        }

        if (Arr::get($filters, 'detail')) {
            $query->where('detail', 'like', Arr::get($filters, 'detail'));
        }

        return $query->orderBy('created_at', 'DESC')
            ->paginate(20);
    }
}