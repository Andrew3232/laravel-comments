# Laravel Comment

## Technologies

* PHP 8.0
* [Laravel 8](https://laravel.com)
* [Docker](https://www.docker.com/)
* [Docker-compose](https://docs.docker.com/compose/)
* [REST API](https://ru.wikipedia.org/wiki/REST)
* [PhpUnit](https://phpunit.de/)
* MySQL 5.7


## Install

The following sections describe dockerized environment.

Just keep versions of installed software to be consistent with the team and production environment (see [Pre-requisites](#pre-requisites) section).


Set your .env vars:
```bash
cp .env.example .env
```

Emails processing .env settings (you can use [mailtrap](https://mailtrap.io/) or your smtp credentials like user@gmail.com):
```dotenv
MAIL_DRIVER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_USERNAME=<mailtrap_key>
MAIL_PASSWORD=<mailtrap_password>
MAIL_PORT=587
MAIL_FROM_ADDRESS=admin@thread.com
MAIL_FROM_NAME="BSA Thread Admin"
```

Start application docker containers:
``` bash
docker-compose up -d
```

Install composer dependencies and generate app key:
```bash
docker exec -it com-app composer install
docker exec -it com-app php artisan key:generate
```

Database migrations install (set proper .env vars)
```bash
docker exec -it com-app php artisan migrate
docker exec -it com-app php artisan db:seed
```

Application server should be ready on `http://localhost:<APP_PORT>`

## Testing

Run phpunit tests
```bash
docker exec -it com-app php artisan test --env=testing
```


## Laravel IDE Helper

For ease of development, you can run the data generation function for the Laravel IDE Helper.
```bash
docker exec -it com-app php artisan ide-helper:generate
docker exec -it com-app php artisan ide-helper:models -N
docker exec -it com-app php artisan ide-helper:meta
```

## Debugging

To debug the application we highly recommend you to use xDebug, it is already pre-installed in dockerized environment, but you should setup your IDE.

You can debug your app with [Telescope](https://laravel.com/docs/9.x/telescope) tool which is installed already
