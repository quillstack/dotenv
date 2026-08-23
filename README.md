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
[![License](https://img.shields.io/packagist/l/quillstack/dotenv)](https://github.com/quillstack/dotenv/blob/main/LICENSE)

Reads a `.env` file into the environment. Values keep their types, and anything it cannot finish
reading it refuses rather than guessing at.

## Why this exists

### It does not expand `${SOMETHING}`, and that is on purpose

Most PHP `.env` libraries resolve one value from another out of the box. This one does not, and
the model it follows is JavaScript's.

**`dotenv` for Node is the most installed `.env` library anywhere, and it does not interpolate.**
Nor does `dotenv-java`. In that world, building values from other values is a second package —
`dotenv-expand` — because it is a second decision: it turns a list of pairs into a small language,
with escaping and ordering and undefined names to settle. Here that package is
[quillstack/dotenv-expand](https://github.com/quillstack/dotenv-expand).

Running the same file through eight implementations across six languages, this is where they
stand:

| Language | Library | Expands `${…}` |
| --- | --- | --- |
| JavaScript | `dotenv` | no — `dotenv-expand` does |
| Java | `dotenv-java` | no |
| **PHP** | **`quillstack/dotenv`** | **no — `quillstack/dotenv-expand` does** |
| PHP | `symfony/dotenv` | yes |
| PHP | `vlucas/phpdotenv` | yes |
| PHP | `josegonzalez/dotenv` | yes |
| Python | `python-dotenv` | yes |
| Ruby | `dotenv` | yes |
| Dart | `dotenv` | yes |
| C | `dotenv-c` | yes |

### The part JavaScript does not do

Node's `dotenv` leaves `URL=${BASE}/v1` as the literal text `${BASE}/v1`, and hands it over
without comment. An application then holds a string that looks like an address and is not one.

**This refuses instead:**

```text
DotenvInterpolationNotSupportedException:
The value of `URL` uses `${...}`, which this package does not expand. Install
quillstack/dotenv-expand to resolve it, or write `\${` for a literal `${`.
```

Which is what makes leaving the second package out safe rather than merely cheap. Nothing is
quietly half-read, in either configuration.

### The same care everywhere else

A `#` after a value is a comment, an `export` prefix is understood, and `hunter2#7` is still a
password — the details a file written for a shell gets right and a naive parser does not. Each
of those was a wrong value handed over without a word until it was fixed.

## Requirements

- PHP 8.1 or newer

## Installation

```shell
composer require quillstack/dotenv
```

## Usage

```text
APP_DEBUG=true
APP_NAME=quillstack
DB_PORT=5432
```

```php
use Quillstack\Dotenv\Dotenv;

(new Dotenv('.env'))->load();
```

```php
env('APP_DEBUG');   // true, a boolean
env('APP_NAME');    // 'quillstack'
env('DB_PORT');     // 5432, a number
```

Values keep the type they plainly have, so `if (env('APP_DEBUG'))` means what it reads as.

### Default values

```php
env('MISSING', 'a default');   // 'a default'
```

### Required keys

Where there is no sensible default, say so and find out at boot rather than at midnight:

```php
$host = required('DATABASE_HOST');
```

```text
DotenvValueNotSetException:
Value not set for key: DATABASE_HOST
```

### Comments after a value

A `#` starts a comment where a shell would treat it as one — after whitespace, and outside
quotes:

```text
DB_PORT=5432 # the default
PASSWORD=hunter2#7            # not a comment: no space before the hash
QUOTED="a # inside quotes"    # the hash inside stays, the one out here goes
```

```php
env('DB_PORT');    // 5432, still a number
env('PASSWORD');   // 'hunter2#7'
env('QUOTED');     // 'a # inside quotes'
```

### The `export` keyword

The same file read by `source` is written with `export`, and it is understood here too:

```text
export DB_PORT=5432
```

```php
env('DB_PORT');   // 5432
```

### Multi-line values

Write `\n` rather than a real line break:

```text
PRIVATE_KEY="line1\nline2\nline3"
```

### Values built from other values

```text
BASE=https://example.org
URL=${BASE}/v1
```

Refused, as above. Install
[quillstack/dotenv-expand](https://github.com/quillstack/dotenv-expand) and it resolves. Where a
`${` means only itself, escape it:

```text
PRICE=\${9.99}
```

```php
env('PRICE');   // '${9.99}'
```

### Reading a file without loading it

`parse()` hands back what the file holds and touches nothing:

```php
$values = (new Dotenv('.env'))->parse();
// ['BASE' => 'https://example.org', 'URL' => '${BASE}/v1']
```

References are left exactly as written, escapes included — which is what lets
`quillstack/dotenv-expand` tell `${BASE}` from `\${BASE}`.

## Benchmark

Measured with [quillstack/benchmark](https://github.com/quillstack/benchmark) on **one file of
34 keys with no interpolation in it**, which all five read identically. Runs are interleaved,
each figure is the median of five, and PHP is 8.5.7.

| | Version |
| --- | --- |
| quillstack/dotenv | v0.7.1 |
| quillstack/dotenv-expand | v0.6.1 |
| symfony/dotenv | v7.4.15 |
| josegonzalez/dotenv | 4.0.0 (on m1/env 2.2.0) |
| vlucas/phpdotenv | v5.6.4 |

Reading that file, once:

| | Per load | Relative | Files loaded | Memory |
| --- | --- | --- | --- | --- |
| **quillstack/dotenv** | **146 µs** | — | 5 | 70 kB |
| quillstack/dotenv + dotenv-expand | 176 µs | 1.20× | 6 | 87 kB |
| symfony/dotenv | 233 µs | 1.60× | 1 | 149 kB |
| josegonzalez/dotenv | 301 µs | 2.06× | 7 | 153 kB |
| vlucas/phpdotenv | 479 µs | 3.27× | 34 | 336 kB |

The second row is this package **with**
[quillstack/dotenv-expand](https://github.com/quillstack/dotenv-expand) on top: adding it costs
about a fifth of the reading time, and it is still the fastest way in this table to resolve a
`.env` at all. That package's README has the same comparison on a file which does use `${…}` —
one this package refuses outright, so it has no row there.

The files-loaded column is where the cold-start difference comes from: `vlucas/phpdotenv` reads
34 files and four packages into memory before parsing anything. Starting a process and loading
those dominates the first read by an order of magnitude more than parsing does, which is why the
per-load figure above is measured warm — it is the part this package controls.

**What the numbers do not say:** the other three expand `${…}` and this one does not, and
`symfony/dotenv` also reads `.env.local` layering and shell command substitution. Being faster
because you do less is not being faster; the row above with `dotenv-expand` added is the like-for
-like one.

## Tests

```shell
composer test
composer stan
```

## The rest of Quillstack

This is one component of [Quillstack](https://github.com/quillstack), a PHP framework which is
as simple to use as it is strict about what it does.

- [quillstack/dotenv-expand](https://github.com/quillstack/dotenv-expand) — values built from other values
- [quillstack/config](https://github.com/quillstack/config) — settings on top of these
- [quillstack/framework](https://github.com/quillstack/framework) — where both are wired in
- [quillstack/local-storage](https://github.com/quillstack/local-storage) — reads the file underneath

## License

MIT — see [LICENSE](https://github.com/quillstack/dotenv/blob/main/LICENSE).
