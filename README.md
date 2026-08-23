# Quillstack Dotenv

[![Tests](https://github.com/quillstack/dotenv/actions/workflows/tests.yml/badge.svg)](https://github.com/quillstack/dotenv/actions/workflows/tests.yml)
[![Latest Version](https://img.shields.io/packagist/v/quillstack/dotenv.svg)](https://packagist.org/packages/quillstack/dotenv)
[![Downloads](https://img.shields.io/packagist/dt/quillstack/dotenv.svg)](https://packagist.org/packages/quillstack/dotenv)
[![PHP Version](https://img.shields.io/packagist/php-v/quillstack/dotenv)](https://packagist.org/packages/quillstack/dotenv)
[![StyleCI](https://github.styleci.io/repos/303510748/shield?branch=main)](https://github.styleci.io/repos/303510748?branch=main)
[![CodeFactor](https://www.codefactor.io/repository/github/quillstack/dotenv/badge)](https://www.codefactor.io/repository/github/quillstack/dotenv)
[![Quality Gate](https://sonarcloud.io/api/project_badges/measure?project=quillstack_dotenv&metric=alert_status)](https://sonarcloud.io/summary/new_code?id=quillstack_dotenv)
[![Coverage](https://sonarcloud.io/api/project_badges/measure?project=quillstack_dotenv&metric=coverage)](https://sonarcloud.io/summary/new_code?id=quillstack_dotenv)
[![Maintainability](https://sonarcloud.io/api/project_badges/measure?project=quillstack_dotenv&metric=sqale_rating)](https://sonarcloud.io/summary/new_code?id=quillstack_dotenv)
[![Reliability](https://sonarcloud.io/api/project_badges/measure?project=quillstack_dotenv&metric=reliability_rating)](https://sonarcloud.io/summary/new_code?id=quillstack_dotenv)
[![Security](https://sonarcloud.io/api/project_badges/measure?project=quillstack_dotenv&metric=security_rating)](https://sonarcloud.io/summary/new_code?id=quillstack_dotenv)
[![Maintainability](https://api.codeclimate.com/v1/badges/df220a266c66f5b4c19c/maintainability)](https://codeclimate.com/github/quillstack/dotenv/maintainability)
[![License](https://img.shields.io/packagist/l/quillstack/dotenv)](https://github.com/quillstack/dotenv/blob/main/LICENSE)

The library for using `.env` files.

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

#### Multi-line values

You can define multi-line values in your `.env` file by using `\n` separator instead of new lines for example:

```text
PRIVATE_KEY="line1\nline2\nline3"
```

#### Comments after a value

A `#` starts a comment where a shell would treat it as one — after whitespace, and outside
quotes:

```text
DB_PORT=5432 # the default
PASSWORD=hunter2#7            # not a comment: no space before the hash
QUOTED="a # inside quotes"    # the hash inside stays, the one out here goes
```

```php
env('DB_PORT');   // 5432, as a number
env('PASSWORD');  // 'hunter2#7'
env('QUOTED');    // 'a # inside quotes'
```

The type is read from what is left once the comment is off, so a commented number is still a
number.

#### The `export` keyword

The same file read by `source` is written with `export`, and it is understood here too:

```text
export DB_PORT=5432
```

```php
env('DB_PORT');   // 5432
```

#### Values built from other values

This package does not expand `${SOMETHING}`, and it does not quietly hand you the text either:

```text
BASE=https://example.org
URL=${BASE}/v1
```

```text
DotenvInterpolationNotSupportedException:
The value of `URL` uses `${...}`, which this package does not expand. Install
quillstack/dotenv-expand to resolve it, or write `\${` for a literal `${`.
```

**A value that looks like an address and is not one is worse than a file that refuses to load.**
Install [quillstack/dotenv-expand](https://github.com/quillstack/dotenv-expand) and it resolves;
leave it out and you are told, with the key and what to do about it.

Where a `${` means only itself, escape it:

```text
PRICE=\${9.99}
```

```php
env('PRICE');   // '${9.99}'
```

#### Reading a file without loading it

`parse()` hands back what the file holds and touches nothing:

```php
$values = (new Dotenv('.env'))->parse();
// ['BASE' => 'https://example.org', 'URL' => '${BASE}/v1']
```

References are left exactly as they were written, escapes included — which is what lets
`quillstack/dotenv-expand` tell `${BASE}` from `\${BASE}`.

### Unit tests
Run tests using a command:

```shell
phpdbg -qrr ./vendor/bin/unit-tests
```

### Docker

```shell
$ docker-compose up -d
$ docker exec -w /var/www/html -it quillstack_dotenv sh
```
