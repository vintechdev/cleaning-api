<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class RecurringPattern
 * @package App
 */
class RecurringPattern extends Model
{
    use SoftDeletes;

    public $timestamps = false;

    /**
     * @var string
     */
    protected $table = 'recurring_patterns';

    /**
     * @var string[]
     */
    protected $fillable = ['id'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function recurringPatternable()
    {
        return $this->morphTo();
    }

    /**
     * @return BelongsTo
     */
    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }
}