## About this API

API that queries another API for info. See assignment for more information.

## Installation

* Execute `composer install` to get vendor libraries and framework

* Set database information on .env file

```APP_NAME=Laravel
APP_ENV=local
APP_KEY=base64:ltuqkXMwJ7OLWKJCI2NIc8wkUzQdClz/PmoYU2d41ok=
APP_DEBUG=true
APP_URL=http://localhost

LOG_CHANNEL=stack

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=carthookapi
DB_USERNAME=user
DB_PASSWORD=password

JSONPlaceholder_URL=https://jsonplaceholder.typicode.com
```

* Execute `php artisan migrate` to setup database tables

* Execute `php artisan serve` to use PHP's built-in development server

## Configure crontab

To get the JsonPlaceholder API cache you have to set the laravel crontab
`* * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1`

Or execute the command `php artisan import:jsonapi`

## Testing

- Download postman collection https://www.getpostman.com/collections/337c6bbac6271e8903b6

- Click on "runner" and Run API
