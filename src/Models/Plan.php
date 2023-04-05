<?php

namespace Smartech\Subscriptions\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Builder;

class Plan extends Model
{

    protected $table = "st_plans";

    protected $with = ["translation"];

    protected $hidden = ["trial_price_interval"];

    protected $appends = ["has_trial", "trial_title"];

    protected $casts = [
        "is_default" => "bool",
    ];

    function features(): BelongsToMany
    {
        return $this->belongsToMany(config("subscriptions.models.feature"), "st_plans_features");
    }

    function subscriptions(): HasMany
    {
        return $this->hasMany(config("subscriptions.models.subscription"));
    }

    function pricings(): HasMany
    {
        return $this->hasMany(config("subscriptions.models.pricing"));
    }

    function translations(): HasMany
    {
        return $this->hasMany(config("subscriptions.models.plan_translation"));
    }

    function translation(): HasOne
    {
        return $this->hasOne(config("subscriptions.models.plan_translation"))->where("lang", app()->getLocale());
    }

    public function trial_price_interval()
    {
        return $this->belongsTo(config("subscriptions.models.duration"), "trial_interval", "slug");
    }

    public function getTrialTitleAttribute()
    {
        if ($this->trial_period > 0) {

            $duration_translation = $this->trial_price_interval->translation;

            if (app()->getLocale() == "ar") {
                $duration_name = $this->trial_period >= 3 && $this->trial_period <= 10 ? $duration_translation->plural_name : $duration_translation->name;
            } else {
                $duration_name = $this->trial_period == 1 ? $duration_translation->name : $duration_translation->plural_name;
            }

            return $this->trial_period . " " . $duration_name . " " . trans("subscriptions::subscriptions.trial");
        }
    }

    /**
     * Free scope
     *
     * @return bool
     */
    public function scopeDefault($query): Builder
    {
        return $query->where("is_default", 1);
    }

    /**
     * check if plan has trial mode
     *
     * @return bool
     */
    public function getHasTrialAttribute() {
        return $this->trial_period > 0;
    }
}
