<?php

namespace Smartech\Subscriptions\Models;

use Illuminate\Database\Eloquent\Model;

class PlanTranslation extends Model {

    protected $table = "st_plans_translations";

    protected $hidden = ["id", "plan_id"];
}
