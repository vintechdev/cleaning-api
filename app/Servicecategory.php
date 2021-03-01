<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Emadadly\LaravelUuid\Uuids;
use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;

class Servicecategory extends Model
{
    use HasApiTokens, Notifiable;
    use Uuids;
    protected $table = 'service_categories';
    use SoftDeletes;
    protected $fillable = ['id'];

    public function __construct(){
        parent::__construct();
    }
    public function services()
    {
        return $this->hasMany(Service::class, 'category_id', 'id');
    }

    /**
     * @return bool
     */
    public function isRecurring(): bool
    {
        return (bool)$this->is_recurring;
    }

    /**
     * @return bool
     */
    public function allowMultipleAddons(): bool
    {
        return (bool) $this->allow_multiple_addons;
    }
}
