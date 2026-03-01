<?php declare(strict_types=1);

namespace Nextras\OrmBrickDateTime;

use Brick\DateTime\Instant;
use Brick\DateTime\TimeZoneRegion;
use Brick\DateTime\ZonedDateTime;
use Nextras\Orm\Exception\InvalidArgumentException;
use Nextras\OrmBrickDateTime\Internals\GenericPropertyWrapper;

/**
 * @extends GenericPropertyWrapper<Instant>
 */
class InstantPropertyWrapper extends GenericPropertyWrapper
{
	public function convertToRawValue(mixed $value): \DateTimeImmutable
	{
		assert($value instanceof Instant);
		$timeZone = TimeZoneRegion::of(date_default_timezone_get());
		return $value->atTimeZone($timeZone)->toNativeDateTimeImmutable();
	}

	public function convertFromRawValue(mixed $value): Instant|null
	{
		// DateTimeInterface is handled because it may be converted to it automatically by Nextras Dbal.
		return match (true) {
			is_string($value) => ZonedDateTime::parse($value)->getInstant(),
			$value instanceof \DateTimeInterface => match (PHP_VERSION_ID >= 80400) {
				true => Instant::of($value->getTimestamp(), $value->getMicrosecond() * 1000),
				false => Instant::of($value->getTimestamp()),
			},
			default => throw new InvalidArgumentException(),
		};
	}

	public function setInjectedValue($value): bool
	{
		if (!$value instanceof Instant) throw new InvalidArgumentException();
		return parent::setInjectedValue($value);
	}
}
