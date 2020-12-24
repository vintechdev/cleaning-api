<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BookingNote extends Model
{
    protected $guarded = ['id'];

    public $timestamps = false;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $bookingId
     * @return $this
     */
    public function setBookingId(int $bookingId): BookingNote
    {
        $this->booking_id = $bookingId;
        return $this;
    }

    /**
     * @param int $userId
     * @return $this
     */
    public function setUserId(int $userId): BookingNote
    {
        $this->user_id = $userId;
        return $this;
    }

    /**
     * @param int $statusId
     * @return $this
     */
    public function setBookingStatusId(int $statusId): BookingNote
    {
        $this->booking_status_id = $statusId;
        return $this;
    }

    /**
     * @param string $notes
     * @return $this
     */
    public function setNotes(string $notes): BookingNote
    {
        $this->notes = $notes;
        return $this;
    }

    /**
     * @return string
     */
    public function getNotes(): string
    {
        return $this->notes;
    }
}
