<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="left">
    <span style="font-size: 24px; font-weight: bold;">Translation Management API</span>
</p>

## About

This is a simple API for managing translations in a Laravel application. It provides endpoints for creating, updating, and deleting translations, as well as listing all translations for a given locale.

## Requirements
- PHP >= 8.4
- Laravel >= 10
- WSL (Windows Subsystem for Linux), if you are using Microsoft OS.
- Docker (Docker Desktop)
- Composer
- Node

## Setup
- Clone the repository

- Open VS Code or your preferred IDE.

- Install Dev Containers plugin (published by Microsoft).

- Open the terminal (Ctrl + `)

- Run `composer install`

- Run `Copy-Item .env.example .env` then update database related variables in .env file
```
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=db_name
DB_USERNAME=db_username
DB_PASSWORD=db_password
```

- Open Command Palette (press Ctrl + Shift + P). Type `Dev Containers: Rebuild and Reopen in Container` then hit Enter.
Once done, run `php artisan migrate` to create the database and tables.

## DB Seeders
- Run `php artisan db:seed --class=DatabaseSeeder` to populate dummy user accounts.
- Run `php artisan db:seed --class=LargeDataSeeder` to populate dummy records in `translations`, `tags` and `tag_translation` tables.

## API Documentation
- Run `php artisan l5-swagger:generate && php artisan route:cache` to generate updated <a href="http://localhost/api/documentation#/" >Swagger API docs</a>.

## Test Cases
- Create `.env.testing` file then add the following variables:
```
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=db_name
DB_USERNAME=db_username
DB_PASSWORD=db_password
```
- Run `php artisan test`

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
