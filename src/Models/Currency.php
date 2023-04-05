<?php

namespace Smartech\Subscriptions\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Currency extends Model
{

    protected $table = "st_currencies";

    protected $with = ["translation"];

    protected $hidden = ["pivot"];

    /**
     * translations relation
     *
     * @return HasMany
     */
    function translations(): HasMany
    {
        return $this->hasMany(config("subscriptions.models.currency_translation"));
    }

    /**
     * translation relation
     *
     * @return HasOne
     */
    function translation(): HasOne
    {
        return $this->hasOne(config("subscriptions.models.currency_translation"))->where("lang", app()->getLocale());
    }
}
