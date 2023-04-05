<?php

namespace Smartech\Subscriptions\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pricing extends Model
{

    protected $table = "st_pricings";

    protected $appends = ["title"];

    protected $hidden = ["price_currency", "price_interval"];

    /**
     * plan relation
     *
     * @return BelongsTo
     */
    public function plan(): BelongsTo
    {
        return $this->belongsTo(config("subscriptions.models.plan"));
    }

    /**
     * price_currency relation
     *
     * @return BelongsTo
     */
    public function price_currency(): BelongsTo
    {
        return $this->belongsTo(config("subscriptions.models.currency"), "currency", "slug");
    }

    /**
     * price_currency relation
     *
     * @return BelongsTo
     */
    public function price_interval(): BelongsTo
    {
        return $this->belongsTo(config("subscriptions.models.duration"), "invoice_interval", "slug");
    }

    /**
     * title getter
     *
     * @return string
     */
    public function getTitleAttribute()
    {
        if ($this->amount == 0) return trans("subscriptions::subscriptions.free");

        return $this->amount . " " . $this->price_currency->translation->name . "/" .  $this->price_interval->translation->name;
    }
}
