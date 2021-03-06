# Laravel AuthLog

[![Build Status](https://travis-ci.org/chapdel/authlog.svg?branch=master)](https://travis-ci.org/chapdel/authlog)
[![Quality Score](https://img.shields.io/scrutinizer/g/chapdel/authlog.svg?style=flat)](https://scrutinizer-ci.com/g/chapdel/authlog)
[![Total Downloads](https://poser.pugx.org/chapdel/authlog/downloads?format=flat)](https://packagist.org/packages/chapdel/authlog)
[![GitHub license](https://img.shields.io/badge/license-MIT-blue.svg?style=flat)](https://raw.githubusercontent.com/chapdel/authlog/master/LICENSE)

## Installation

> Laravel AuthLog requires Laravel 5.5 or higher, and PHP 7.0+.

You may use Composer to install Laravel AuthLog into your Laravel project:

    composer require chapdel/authlog

### Configuration

After installing the Laravel AuthLog, publish its config, migration and view, using the `vendor:publish` Artisan command:

    php artisan vendor:publish --provider="Chapdel\AuthLog\AuthLogServiceProvider"

Next, you need to migrate your database. The Laravel AuthLog migration will create the table your application needs to store authentication logs:

    php artisan migrate

Finally, add the `AuthLogable` and `Notifiable` traits to your authenticatable model (by default, `App\User` model). These traits provides various methods to allow you to get common authentication log data, such as last login time, last login IP address, and set the channels to notify the user when login from a new device:

```php
use Illuminate\Notifications\Notifiable;
use Chapdel\AuthLog\AuthLogable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable, AuthLogable;
}
```

### Basic Usage

Get all authentication logs for the user:

```php
User::find(1)->authentications;
```

Get the user last login info:

```php
User::find(1)->lastLoginAt();

User::find(1)->lastLoginIp();
```

Get the user previous login time & ip address (ignoring the current login):

```php
auth()->user()->previousLoginAt();

auth()->user()->previousLoginIp();
```

### Notify login from a new device

Notifications may be sent on the `mail`, `nexmo`, and `slack` channels. By default notify via email.

You may define `notifyAuthLogVia` method to determine which channels the notification should be delivered on:

```php
/**
 * The Authentication Log notifications delivery channels.
 *
 * @return array
 */
public function notifyAuthLogVia()
{
    return ['nexmo', 'mail', 'slack'];
}
```

Of course you can disable notification by set the `notify` option in your `config/authlog.php` configuration file to `false`:

```php
'notify' => env('AUTHLOG_NOTIFY', false),
```

### Clear old logs

You may clear the old authentication log records using the `authlog:clear` Artisan command:

    php artisan authlog:clear

Records that is older than the number of days specified in the `older` option in your `config/authlog.php` will be deleted:

```php
'older' => 365,
```

## Contributing

Thank you for considering contributing to the Laravel AuthLog.

## License

Laravel AuthLog is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT).
