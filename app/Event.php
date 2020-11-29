<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Event
 * @package App
 */
class Event extends Model
{
    use SoftDeletes;

    /**
     * @var string
     */
    protected $table = 'events';

    /**
     * @var string[]
     */
    protected $fillable = ['id'];

    public function recurringPattern()
    {
        return $this->hasOne(RecurringPattern::class);
    }
}