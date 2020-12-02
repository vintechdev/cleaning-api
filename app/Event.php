<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Event
 * @package App
 */
class Event extends Model
{
    use SoftDeletes;

    public $timestamps = false;

    /**
     * @var string
     */
    protected $table = 'events';

    /**
     * @var string[]
     */
    protected $fillable = ['id'];

    /**
     * @return HasOne
     */
    public function recurringPattern(): HasOne
    {
        $this->hasOne(RecurringPattern::class);
    }
}