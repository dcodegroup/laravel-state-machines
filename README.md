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
composer require dcodegroup/state-machines:dev-main
```

You can publish the migration files for statuses and statusables via the artisan command:

```
php artisan vendor:publish --tag=statuses-migrations
```
This command allows you to customize and adapt the migrations according to your specific needs.