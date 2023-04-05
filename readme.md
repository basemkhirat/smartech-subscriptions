## Smartech Subscriptions

Manage user subscriptions for Laravel/Lumen apps

<br />

## Requirements



- `php` >= 5.6.6 
- `laravel/laravel` >= 5.* or `laravel/lumen` >= 5.* or `composer application`

<br />

## Installation

<br />

### <u>Laravel Installation</u>


##### 1) Install package using composer.

```bash
$ composer require smartech/subscriptions
```

##### 2) Add package service provider (< laravel 5.5).

```php
Smartech\Subscriptions\ServiceProvider::class
```

##### 4) Publishing.

```bash
$ php artisan vendor:publish --provider="Smartech\Subscriptions\ServiceProvider"
```

<br />

### <u>Lumen Installation</u>

##### 1) Install package using composer.
```bash
$ composer require Smartech\Subscriptions
```

##### 2) Add package service provider in `bootstrap/app.php`.

```php
$app->register(Smartech\Subscriptions\ServiceProvider::class);
```
	
##### 3) Copy the config file: `vendor/smartech/subscriptions/config/subscriptions.php` to the application config directory.
	
	
##### 4) Set Lumen to parse the configuration file.

```php
$app->configure('subscriptions');
```

<br />

## Configuration

use the `HasSubscription` trait with the `User` model.

```php
<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Smartech\Subscriptions\Traits\HasSubscription;

class User extends Authenticatable 
{
    use HasSubscription;

    ......
}

```

<br />

## Usage

### Subscribe to a plan

- To subscribe to a plan, you must either specify the pricing or request a trial period (if the plan supports requesting a trial period)
- Setting the pricing by defining the period and its price for each plan, and each plan can have multiple pricings (monthly, yearly or others).

```php
$user = auth("api")->user();

try {
    $user->setPlan("premium") // the plan slug
        ->setPricing(1) // the pricing ID
        ->subscribe(); // return bool

    // user subscribed successfully

}  catch (SubscriptionException $error) {

    // All available exceptions

    if ($error instanceof PlanNotFoundException) {
        return response()->json("Plan not found");
    }

    if ($error instanceof PricingNotFoundException) {
        return response()->json("Pricing not found");
    }

    if ($error instanceof SubscriptionExistException) {
        return response()->json("User has already subscribed to a plan");
    }
}
```

<br />

### Subscribe to a a plan with trial period

- If the requested plan supports requesting a trial period, the following code will subscribe the user to this plan with the period defined for each plan in the plans table.
- `setPricing` should not be called here as trial period is always free.

```php

$user = auth("api")->user();

try {

$user->setPlan("premium")
    ->setTrial()
    ->subscribe(); // return bool

    // user subscribed successfully

}  catch (SubscriptionException $error) {

    // All available exceptions

    if ($error instanceof PlanNotFoundException) {
        return response()->json("Plan not found");
    }

    if ($error instanceof TrialNotSupportedException) {
        return response()->json("Trial is not supported for this plan");
    }

    if ($error instanceof TrialExistException) {
        return response()->json("Trial period has already been requested before.");
    }
}
```

<br />

### Change the subscription plan
- Changing the plan will set a new plan to the user and set a new period (`started_at`, `ended_at`) based on the pricing ID.

```php

try {
    $user = auth("api")->user();
    
    $user->setPlan("premium") // the new plan slug
        ->setPricing(2) // the pricing ID
        ->change();

}  catch (SubscriptionException $error) {

    if ($error instanceof PlanNotFoundException) {
        return response()->json("Plan not found");
    }

    if ($error instanceof PricingNotFoundException) {
        return response()->json("Pricing not found");
    }
}
```

<br />

### Renew the subscription
- Renewing the subscription will only set a new period (`started_at`, `ended_at`) based on the current pricing ID.

```php
/*
* can_be_renewed will check if:
* - The subscription is ended.
* - The subscription is not trial.
* - The subscribed plan is not default.
*/
if($user->subscription->can_be_renewed) {
    $user->subscription->renew();
}
```

<br />

### Cancel the subscription
- Canceling the subscription will set the `canceled_at` field 
- By default, the subscription will still valid until the end of the current period.

```php
/*
* can_be_renewed will check if:
* - The subscription is not trial.
* - The subscribed plan is not default as it can not be canceled.
*/

if($user->subscription->can_be_canceled) {
    $user->subscription->cancel();
}
```

- If we want to cancel the subscription immediately, just call ` $user->subscription->cancel(true)`.

<br />

### Activate/Deactivate the subscription
- Activating/Deactivating the subscription will set the `is_active` field.

```php
$user->subscription->activate();
$user->subscription->deactivate();
```

<br />

## Models

### Subscription Model

```php
$user = auth("api")->user();

// Getting user subscription

$subscription = $user->subscription;

// Getting user subscription plan 

$plan = $user->subscription->plan;

// Getting the user subscription pricing

$pricing = $user->subscription->pricing;

// Check if the user subscription is trial

$subscription->is_trial; // return bool

// Check if the user subscription is activated

$subscription->is_active; // return bool

// Check if the user subscription is ended

$subscription->is_ended; // return bool

// Check if the user is valid (activated and not ended)

$subscription->is_valid; // return bool

// Check if the user has canceled his subscription

$subscription->is_canceled; // return bool

/// Fetch all trial subscriptions

$subscriptions = app("subscriptions.models.subscription")->trial()->get();

// Fetch all valid/invalid subscriptions
// The valid subscription means both (activated and the period is not ended)

$subscriptions = app("subscriptions.models.subscription")->valid()->get();
$subscriptions = app("subscriptions.models.subscription")->invalid()->get();

// Fetch only active/inactive subscriptions

$subscriptions = app("subscriptions.models.subscription")->active()->get();
$subscriptions = app("subscriptions.models.subscription")->inactive()->get();

// Fetch only current/expired subscriptions

$subscriptions = app("subscriptions.models.subscription")->inPeriod()->get();
$subscriptions = app("subscriptions.models.subscription")->notInPeriod()->get();
// 
```

<br />

### Plan Modal

```php
// Getting user subscription plan.

auth("api")->user()->subscription->plan

// Getting the default plan

$plan = app("subscriptions.models.plan")->default()->first();

// Query the plan model

$plan = app("subscriptions.models.plan")->where("slug", "premium")->first();

// check if there is a trial support for this plan

$plan->has_trial; // return bool


// check if this plan is the default plan

$plan->is_default; // return bool

// Getting all plan pricings
// the pricings determine what price to pay for a period of time (monthly, yearly or a custom period)

$plan->pricings;

// Getting all plan features

$plan->features;

// Getting all available translations of all languages

$plan->translations;

// Getting the current translation based on user language

$plan->translation;
```

<br />

### User Model

### Get user subscription

```php
auth("api")->user()->subscription;
```

<br />

### check if user has specific plan

```php
auth("api")->user()->hasPlan("premium"); // return bool
```

<br />

### check if user has specific feature

```php
auth("api")->user()->hasFeature("ad_free"); // return bool
```

<br />


## Extending Models

All models are binded into IOC and listed in `config/subscription.php`.

As example, we want to extend the `Plan` model.


1. Edit `config/subscription.php` and set the `models.plan` to have the new plan model class `\App\Models\Plan::class`.

2. define the new class extends the parent plan class

```php
<?php

namespace App\Models;

class Plan extends \Smartech\Subscriptions\Models\Plan
{
    // we can override model attributes/methods or create new methods here.
}

```

3. Now you can call it from `app("subscriptions.models.plan")` or from `\App\Models\Plan`.
