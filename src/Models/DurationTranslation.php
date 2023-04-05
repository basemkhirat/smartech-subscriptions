<?php

namespace Smartech\Subscriptions\Models;

use Illuminate\Database\Eloquent\Model;

class DurationTranslation extends Model {

    protected $table = "st_durations_translations";

    protected $hidden = ["id", "duration_id"];
}
