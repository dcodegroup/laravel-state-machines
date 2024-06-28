# State machines for Laravel

This Laravel package simplifies the creation of state machines for your models,
inspired by [Jake Bennett's talk](https://www.youtube.com/watch?v=1A1xFtlDyzU) 
at Laracon US Nashville in 2023.

## Features
* Database migrations for managing statuses and tracking status history.
* Artisan command to effortlessly generate state machines with states and transitions, for your specified model.

## Installation & setup

You can install the package via composer:

```
composer require dcodegroup/laravel-state-machines
```

You can publish the migration files for statuses and statusables via the artisan command:

```
php artisan vendor:publish --tag=statuses-migrations
```
This command allows you to customize and adapt the migrations according to your specific needs.

## Creating state machines

Run the `php artisan make:state-machine` command to create your first state machines. 
The command accepts a model as an argument, states and transitions as options.

```
php artisan make:state-machine User --states=Accepted,Pending,Rejected --transitions=approve,deny
```

This example will generate the following state machines under the `App/StateMachines/User` directory:
* UserStateContract.php which all transitions as methods.
* BaseUserState.php which implements the UserStateContract and automatically throws an exception for each transition.
* AcceptedState.php, PendingState.php, RejectedState.php which extend the BaseUserState.

You can also change the default namespace for your model if they are not stored under `App/Models` with the `--namespace` 
option. e.g. `--namespace=App\\ACL`.

## Configuring statuses on a model.

Add the `HasStates` trait to your model and add a `status_id` column to that same model.
Implement the state method on your model, so it returns the user state contract.

```php
use App\StateMachines\Users\AcceptedState;
use App\StateMachines\Users\PendingState;
use App\StateMachines\Users\RejectedState;
use http\Exception\InvalidArgumentException;

public function state()
{
    return match($this->status->machine_name) {
        'accepted' => new AcceptedState($this),
        'pending' => new PendingState($this),
        'rejected' => new RejectedState($this),
        'default' => throw new InvalidArgumentException('Invalid state'),
    }
}
```

## Using state

Call the state method from you model and chain it with a transition method. e.g.

```php
$user->state()->approve();
```

## Default model state

You can set a default state on your model by overriding the `$defaultState` property defined in the `HasStates` trait.

## Assigning a state

You can change the status of your model by using the `setStatus()` method which accepts a status machine name as an argument.
