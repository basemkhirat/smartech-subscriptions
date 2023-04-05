<?php

namespace Smartech\Subscriptions\Models;

use Illuminate\Database\Eloquent\Model;

class CurrencyTranslation extends Model {

    protected $table = "st_currencies_translations";

    protected $hidden = ["id", "currency_id"];
}
