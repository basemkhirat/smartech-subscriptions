<?php

namespace Smartech\Subscriptions\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;
use Smartech\Subscriptions\Exceptions\PlanNotFoundException;
use Smartech\Subscriptions\Exceptions\PricingNotFoundException;
use Smartech\Subscriptions\Exceptions\SubscriptionExistException;
use Smartech\Subscriptions\Exceptions\TrialExistException;
use Smartech\Subscriptions\Services\Period;

class Subscription extends Model
{

    protected $table = "st_subscriptions";

    public $dates = [
        "started_at",
        "ended_at",
        "canceled_at"
    ];

    protected $appends = [
        "is_valid",
        "is_canceled",
        "is_ended",
        "can_be_renewed",
        "can_be_canceled"
    ];

    protected $casts = [
        "is_trial" => "bool",
        "is_active" => "bool"
    ];

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
     * pricing relation
     *
     * @return BelongsTo
     */
    public function pricing(): BelongsTo
    {
        return $this->belongsTo(config("subscriptions.models.pricing"));
    }

    /**
     * active scope
     *
     * @return bool
     */
    public function scopeActive($query): Builder
    {
        return $query->where("is_active", 1);
    }

    /**
     * inactive scope
     *
     * @return bool
     */
    public function scopeInactive($query): Builder
    {
        return $query->where("is_active", 0);
    }

    /**
     * trial scope
     *
     * @return bool
     */
    public function scopeTrial($query, $is_trial = 1): Builder
    {
        return $query->where("is_trial", $is_trial);
    }

    /**
     * inPeriod scope
     *
     * @return bool
     */
    public function scopeInPeriod($query): Builder
    {
        return $query->where(function ($query) {
            $query->where("ended_at", ">", Carbon::now())
                ->orWhereNull("ended_at");
        });
    }

    /**
     * notInPeriod scope
     *
     * @return bool
     */
    public function scopeNotInPeriod($query): Builder
    {
        return $query->where(function ($query) {
            $query->whereNotNull("ended_at")
                ->where("ended_at", "<", Carbon::now());
        });
    }

    /**
     * valid scope
     *
     * @return bool
     */
    public function scopeValid($query): Builder
    {
        return $query->active()->inPeriod();
    }

    /**
     * inValid scope
     *
     * @return bool
     */
    public function scopeInvalid($query): Builder
    {
        return $query->where(function ($query) {
            $query->inactive()->orWhere(function ($query) {
                $query->whereNotNull("ended_at")
                    ->where("ended_at", "<", Carbon::now());
            });
        });
    }

    /**
     * Check if a valid subscription
     *
     * @return bool
     */
    public function getIsValidAttribute()
    {
        return $this->is_active && !$this->is_ended;
    }

    /**
     * Activate the subscription.
     *
     * @return bool
     */
    public function activate(): bool
    {
        $this->is_active = true;

        return $this->save();
    }

    /**
     * Deactivate the subscription.
     *
     * @return bool
     */
    public function deactivate(): bool
    {
        $this->is_active = false;

        return $this->save();
    }

    /**
     * Cancel the subscription.
     *
     * @param bool $immediately
     *
     * @return bool
     */
    public function cancel($immediately = false)
    {
        $this->canceled_at = Carbon::now();

        if ($immediately) {
            $this->ended_at = $this->canceled_at;
        }

        return $this->save();
    }

    /**
     * Check if subscription is canceled.
     *
     * @return bool
     */
    public function getIsCanceledAttribute(): bool
    {
        return $this->canceled_at ? Carbon::now()->gte($this->canceled_at) : false;
    }

    /**
     * Check if subscription is ended.
     *
     * @return bool
     */
    public function getIsEndedAttribute(): bool
    {
        return $this->ended_at ? Carbon::now()->gte($this->ended_at) : false;
    }

    /**
     * Check if subscription can be renewed
     *
     * @return bool
     */
    public function getCanbeRenewedAttribute(): bool
    {
        return $this->is_ended && !$this->is_trial && !$this->plan->is_default;
    }

    /**
     * Check if subscription can be canceled
     *
     * @return bool
     */
    public function getCanbeCanceledAttribute(): bool
    {
        return !$this->is_canceled && !$this->plan->is_default;
    }

    /**
     * Renew subscription.
     *
     * @return bool
     */
    public function renew()
    {
        $period = new Period($this->pricing->invoice_interval, $this->pricing->invoice_period);

        $this->started_at = $period->getStartDate();
        $this->ended_at = $period->getEndDate();
        $this->canceled_at = NULL;

        return $this->save();
    }

    /**
     * Set user
     *
     * @return Subscription
     */
    public function setUser($user_id): Subscription
    {
        $this->user_id = $user_id;

        return $this;
    }

    /**
     * Set plan
     *
     * @return Subscription
     */
    public function setPlan($plan_id): Subscription
    {
        $this->plan_id = $plan_id;

        return $this;
    }

    /**
     * Set pricing
     *
     * @return Subscription
     */
    public function setPricing($pricing_id): Subscription
    {
        $pricing = app("subscriptions.models.pricing")->where("id", $pricing_id)
            ->where("plan_id", $this->plan_id)->first();

        if (!$pricing) {
            throw new PricingNotFoundException("Invalid pricing ID: " . $pricing_id . " for the selected plan.");
        }

        $period = new Period($pricing->invoice_interval, $pricing->invoice_period);

        $this->is_trial = 0;
        $this->pricing_id = $pricing_id;
        $this->started_at = $period->getStartDate();
        $this->ended_at = $period->getEndDate();
        $this->amount = $pricing->amount;
        $this->currency = $pricing->currency;

        return $this;
    }

    /**
     * Set if trial requested
     *
     * @return Subscription
     */
    public function setTrial(): Subscription
    {
        $plan = app("subscriptions.models.plan")->where("id", $this->plan_id)->first();

        if (!$plan) {
            throw new PlanNotFoundException("Plan \"$plan->slug\" not found.");
        }

        // if plan doesn't support trial mode

        if ($plan->trial_period == 0) {
            throw new TrialNotSupportedException("Trial mode is not supported for " . $plan->slug . "plan.");
        }

        // if user has requested the trial mode before

        $trial = app("subscriptions.models.trial")->where("plan_id", $this->plan_id)
            ->where("user_id", $this->user_id)
            ->first();

        if ($trial) {
            throw new TrialExistException("Trial mode has been requested for this plan before.");
        }

        $period = new Period($plan->trial_interval, $plan->trial_period);

        $this->is_trial = 1;
        $this->pricing_id = 0;
        $this->started_at = $period->getStartDate();
        $this->ended_at = $period->getEndDate();

        return $this;
    }

    /**
     * Subscribe to the plan
     *
     * @return bool
     */
    public function subscribe(): bool
    {
        $subscription = app("subscriptions.models.subscription")->where("user_id", $this->user_id)->first();

        if ($subscription && $subscription->plan) {
            if ($subscription->plan->id == $this->plan->id) {
                throw new SubscriptionExistException("You have already subscribed to the " . $subscription->plan->slug . " plan.");
            } else {
                throw new SubscriptionExistException("You have already subscribed to another plan: " . $subscription->plan->slug . ".");
            }
        }

        if ($this->is_trial == 1) {

            $trial = app("subscriptions.models.trial");

            $trial->user_id = $this->user_id;
            $trial->plan_id = $this->plan_id;
            $trial->started_at = $this->started_at ?? NULL;
            $trial->ended_at = $this->ended_at ?? NULL;

            $trial->save();
        }

        // create a new subscription

        $subscription = app("subscriptions.models.subscription");

        $subscription->user_id = $this->user_id;
        $subscription->plan_id = $this->plan_id;
        $subscription->pricing_id = $this->pricing_id ?? 0;
        $subscription->amount = $this->amount ?? 0;
        $subscription->currency = $this->currency ?? 'usd';
        $subscription->is_active = $this->is_active ?? 1;
        $subscription->is_trial = $this->is_trial ?? 0;
        $subscription->started_at = $this->started_at ?? NULL;
        $subscription->ended_at = $this->ended_at ?? NULL;

        return $subscription->save();
    }

    /**
     * Change subscription plan.
     * @param String $plan_slug
     *
     * @return bool
     */
    public function change()
    {
        $plan = app("subscriptions.models.plan")->where("id", $this->plan_id)->first();

        if (!$plan) {
            throw new PlanNotFoundException("Plan: " . $plan->slug . " not found.");
        }

        if ($this->is_trial == 1) {

            $trial = app("subscriptions.models.trial");

            $trial->user_id = $this->user_id;
            $trial->plan_id = $this->plan_id;
            $trial->started_at = $this->started_at ?? NULL;
            $trial->ended_at = $this->ended_at ?? NULL;

            $trial->save();
        } else {
            if (!$this->pricing_id) {
                throw new PricingNotFoundException("Pricing not found");
            }
        }

        // Attach the new plan to subscription

        $this->plan_id = $plan->id;
        $this->started_at = $this->started_at ?? NULL;
        $this->ended_at = $this->ended_at ?? NULL;
        $this->canceled_at = NULL;

        return $this->save();
    }
}
