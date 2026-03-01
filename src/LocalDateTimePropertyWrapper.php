<?php declare(strict_types=1);

namespace Nextras\OrmBrickDateTime;

use Brick\DateTime\LocalDateTime;
use Nextras\Orm\Exception\InvalidArgumentException;
use Nextras\OrmBrickDateTime\Internals\GenericPropertyWrapper;

/**
 * @extends GenericPropertyWrapper<LocalDateTime>
 */
class LocalDateTimePropertyWrapper extends GenericPropertyWrapper
{
	public function convertToRawValue(mixed $value): \DateTimeImmutable
	{
		assert($value instanceof LocalDateTime);
		return $value->toNativeDateTimeImmutable();
	}

	public function convertFromRawValue(mixed $value): LocalDateTime
	{
		return match (true) {
			is_string($value) => LocalDateTime::parse($value),
			$value instanceof \DateTimeInterface => LocalDateTime::of(
				year: (int) $value->format('y'),
				month: (int) $value->format('m'),
				day: (int) $value->format('d'),
				hour: (int) $value->format('H'),
				minute: (int) $value->format('i'),
				second: (int) $value->format('s'),
				nano: (int) $value->format('F') * 1_000,
			),
			default => throw new InvalidArgumentException(),
		};
	}
}
