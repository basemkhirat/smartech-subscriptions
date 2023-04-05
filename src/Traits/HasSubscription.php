<?php

namespace Smartech\Subscriptions\Traits;

use Illuminate\Database\Eloquent\Relations\HasOne;
use Smartech\Subscriptions\Exceptions\PlanNotFoundException;
use Smartech\Subscriptions\Models\Subscription;

trait HasSubscription
{

    /**
     * Subscription relation
     *
     * @return HasOne
     */
    public function subscription(): HasOne
    {
        return $this->hasOne(config("subscriptions.models.subscription"));
    }

    /**
     * Check if user has specific plan
     *
     * @param String $plan_slug
     *
     * @return bool
     */
    public function hasPlan($plan_slug): bool
    {
        return $this->subscription?->plan?->slug == $plan_slug;
    }

    /**
     * Check if user can use specific feature
     *
     * @param String $feature_slug
     *
     * @return bool
     */
    public function hasFeature($feature_slug): bool
    {
        $valid_subscription = $this->subscription()->valid()->first();

        return !!$valid_subscription->plan?->features->where("slug", $feature_slug)->first();
    }

    /**
     * Subscribe to a plan
     *
     * @param String $plan_slug
     *
     * @return Subscription
     */
    public function setPlan($plan_slug): Subscription
    {
        $plan = app("subscriptions.models.plan")->where("slug", $plan_slug)->first();

        if (!$plan) {
            throw new PlanNotFoundException("Plan \"$plan_slug\" not found.");
        }

        $subscription = app("subscriptions.models.subscription")->where("user_id", $this->id)->first();

        if ($subscription) {
            $subscription->setPlan($plan->id);
            return $subscription;
        }

        $subscription = app("subscriptions.models.subscription");

        $subscription->setUser($this->id);
        $subscription->setPlan($plan->id);

        return $subscription;
    }
}
