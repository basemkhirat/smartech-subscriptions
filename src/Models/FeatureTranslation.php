<?php

namespace Smartech\Subscriptions\Models;

use Illuminate\Database\Eloquent\Model;

class FeatureTranslation extends Model {

    protected $table = "st_features_translations";

    protected $hidden = ["id", "feature_id"];
}
