# Nextras ORM — brick/date-time Integration

[![Build Status](https://img.shields.io/github/actions/workflow/status/nextras/orm-brick-date-time/build.yml?branch=main)](https://github.com/nextras/orm-brick-date-time/actions?query=workflow%3ABuild+branch%3Amain)
[![Downloads this Month](https://img.shields.io/packagist/dm/nextras/orm-brick-date-time.svg?style=flat)](https://packagist.org/packages/nextras/orm-brick-date-time)
[![Stable Version](https://img.shields.io/packagist/v/nextras/orm-brick-date-time.svg?style=flat)](https://packagist.org/packages/nextras/orm-brick-date-time)
[![Packagist PHP Version Support](https://img.shields.io/packagist/php-v/nextras/orm-brick-date-time)](https://packagist.org/packages/nextras/orm-brick-date-time)

Property wrappers and ORM extension integrating [brick/date-time](https://github.com/brick/date-time) types with [Nextras ORM](https://github.com/nextras/orm).

## Supported Types

| PHP type                       | MySQL Storage      | Postgres Storage   | DBAL modifier |
|--------------------------------|--------------------|--------------------|---------------|
| `Brick\DateTime\Instant`       | `TIMESTAMP`        | `TIMESTAMPTZ`      | `%dt`         |
| `Brick\DateTime\LocalDateTime` | `DATETIME`         | `TIMESTAMP`        | `%ldt`        |
| `Brick\DateTime\LocalDate`     | `DATE` / `VARCHAR` | `DATE` / `VARCHAR` | `%s`          |

## Installation

```bash
composer require nextras/orm-brick-date-time
```

## Usage

Register the extension in your Nette configuration. Typically, using NEON configuration:


```neon
extensions:
    nextras.orm: Nextras\Orm\Bridges\NetteDI\OrmExtension

nextras.orm:
    extensions:
        @Nextras\OrmBrickDateTime\OrmBrickDateTimeExtension

services:
    - Nextras\OrmBrickDateTime\OrmBrickDateTimeExtension
```

Then use brick/date-time types in your entity property annotations:

```php
use Brick\DateTime\Instant;
use Brick\DateTime\LocalDate;
use Brick\DateTime\LocalDateTime;
use Nextras\Orm\Entity\Entity;

/**
 * @property int              $id
 * @property Instant          $createdAt
 * @property Instant|null     $deletedAt
 * @property LocalDate        $bornOn
 * @property LocalDateTime    $scheduledAt
 */
class User extends Entity
{
}
```

The extension automatically:
- assigns the correct property wrapper based on the declared type,
- configures the DBAL column modifier so values are persisted correctly.


## License

MIT. See full [license](license.md).
