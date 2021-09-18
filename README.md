# Quillstack Dotenv

[![Build Status](https://app.travis-ci.com/quillstack/dotenv.svg?branch=main)](https://app.travis-ci.com/quillstack/dotenv)
[![Downloads](https://img.shields.io/packagist/dt/quillstack/dotenv.svg)](https://packagist.org/packages/quillstack/dotenv)
[![Coverage](https://sonarcloud.io/api/project_badges/measure?project=quillstack_dotenv&metric=coverage)](https://sonarcloud.io/dashboard?id=quillstack_dotenv)
[![Lines of Code](https://sonarcloud.io/api/project_badges/measure?project=quillstack_dotenv&metric=ncloc)](https://sonarcloud.io/dashboard?id=quillstack_dotenv)
[![StyleCI](https://github.styleci.io/repos/303510748/shield?branch=main)](https://github.styleci.io/repos/303510748?branch=main)
[![CodeFactor](https://www.codefactor.io/repository/github/quillstack/dotenv/badge)](https://www.codefactor.io/repository/github/quillstack/dotenv)
![Packagist License](https://img.shields.io/packagist/l/quillstack/dotenv)
[![Reliability Rating](https://sonarcloud.io/api/project_badges/measure?project=quillstack_dotenv&metric=reliability_rating)](https://sonarcloud.io/dashboard?id=quillstack_dotenv)
[![Maintainability](https://api.codeclimate.com/v1/badges/df220a266c66f5b4c19c/maintainability)](https://codeclimate.com/github/quillstack/dotenv/maintainability)
[![Security Rating](https://sonarcloud.io/api/project_badges/measure?project=quillstack_dotenv&metric=security_rating)](https://sonarcloud.io/dashboard?id=quillstack_dotenv)
![Packagist PHP Version Support](https://img.shields.io/packagist/php-v/quillstack/dotenv)

The library for using `.env` files. You can find the full documentation on the website: \
https://quillstack.org/dotenv

The `.env` should be used for sensitive information like passwords, hosts, keys, credentials, and all other values that 
can be changed depending on the environment, e.g., debug mode settings or logs level. 

### Installation

To install this package, run the standard command using _Composer_:

```shell
composer require quillstack/dotenv
```

### Usage

You can use Quillstack Dotenv package when you want to use `.env` files in your project.

#### Simple usage

If you created the `.env` file in the root directory of your proejct:

```shell
APP_DEBUG=true
```

You can load this `.env` file in your application:

```php
$dotenv = new Dotenv('.env');
$dotenv->load();
```

Every time you need to know if your application works in debug mode, you can check it using this helper function:

```php
if (env('APP_DEBUG')) {
    echo 'Debug mode';
}
```

#### Default values

You can also define a default value depending on the context:

```php
if (env('APP_DEBUG', false)) {
    echo 'Debug mode';
}
```

#### Required keys

You can use another helper method for required keys. If required key is not found
an exception will be thrown:

```php
$dbHost = required('DATABASE_HOST');
```

The result if the key `DATABASE_HOST` is not set in the `.env` file:

```text
DotenvValueNotSetException:
Value not set for key: DATABASE_HOST
```

### Unit tests
Run tests using a command:

```shell
phpdbg -qrr vendor/bin/phpunit
```

Check the tests coverage:

```shell
phpdbg -qrr vendor/bin/phpunit --coverage-html coverage tests
```

### Docker

```shell
$ docker-compose up -d
$ docker exec -w /var/www/html -it quillstack_dotenv sh
```
