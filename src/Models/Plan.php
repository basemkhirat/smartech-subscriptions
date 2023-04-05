<?php

namespace Smartech\Subscriptions\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Plan extends Model
{

    protected $table = "st_plans";

    protected $with = ["translation"];

    protected $hidden = ["trial_price_interval"];

    protected $appends = ["has_trial", "trial_title"];

    protected $casts = [
        "is_default" => "bool",
    ];

    /**
     * features relation
     *
     * @return BelongToMany
     */
    function features(): BelongsToMany
    {
        return $this->belongsToMany(config("subscriptions.models.feature"), "st_plans_features");
    }

    /**
     * subscriptions relation
     *
     * @return HasMany
     */
    function subscriptions(): HasMany
    {
        return $this->hasMany(config("subscriptions.models.subscription"));
    }

    /**
     * pricings relation
     *
     * @return HasMany
     */
    function pricings(): HasMany
    {
        return $this->hasMany(config("subscriptions.models.pricing"));
    }

    /**
     * translations relation
     *
     * @return HasMany
     */
    function translations(): HasMany
    {
        return $this->hasMany(config("subscriptions.models.plan_translation"));
    }

    /**
     * translation relation
     *
     * @return HasOne
     */
    function translation(): HasOne
    {
        return $this->hasOne(config("subscriptions.models.plan_translation"))->where("lang", app()->getLocale());
    }

    /**
     * trial price interval relation
     *
     * @return BelongsTo
     */
    public function trial_price_interval(): BelongsTo
    {
        return $this->belongsTo(config("subscriptions.models.duration"), "trial_interval", "slug");
    }

    /**
     * trial_title getter
     *
     * @return string
     */
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
     * default scope
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
    public function getHasTrialAttribute()
    {
        return $this->trial_period > 0;
    }
}
