<?php

namespace Smartech\Subscriptions\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Trial extends Model
{

    protected $table = "st_trials";

    public $dates = [
        "started_at",
        "ended_at"
    ];

    /**
     * plan relation
     *
     * @return BelongTo
     */
    public function plan(): BelongsTo
    {
        return $this->belongsTo(config("subscriptions.models.plan"));
    }
}
