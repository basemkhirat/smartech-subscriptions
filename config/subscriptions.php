<?php

return [

    /**
     * All models are binded into IOC
     * Prefered to access the models using app() function with its key.
     * @example use app('subscriptions.models.plan') to access plan model object
     */

    "models" => [
        "subscription" => \Smartech\Subscriptions\Models\Subscription::class,
        "plan" => \Smartech\Subscriptions\Models\Plan::class,
        "plan_translation" => \Smartech\Subscriptions\Models\PlanTranslation::class,
        "pricing" => \Smartech\Subscriptions\Models\Pricing::class,
        "trial" => \Smartech\Subscriptions\Models\Trial::class,
        "feature" => \Smartech\Subscriptions\Models\Feature::class,
        "feature_translation" => \Smartech\Subscriptions\Models\FeatureTranslation::class,
        "duration" => \Smartech\Subscriptions\Models\Duration::class,
        "duration_translation" => \Smartech\Subscriptions\Models\DurationTranslation::class,
        "currency" => \Smartech\Subscriptions\Models\Currency::class,
        "currency_translation" => \Smartech\Subscriptions\Models\CurrencyTranslation::class,
    ]
];
