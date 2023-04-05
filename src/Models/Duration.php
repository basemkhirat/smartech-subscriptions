<?php

namespace Smartech\Subscriptions\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Duration extends Model
{

    protected $table = "st_durations";

    protected $with = ["translation"];

    protected $hidden = ["pivot"];

    function translations(): HasMany
    {
        return $this->hasMany(config("subscriptions.models.duration_translation"));
    }

    function translation(): HasOne
    {
        return $this->hasOne(config("subscriptions.models.duration_translation"))->where("lang", app()->getLocale());
    }
}
