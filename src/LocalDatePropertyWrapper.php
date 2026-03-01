<?php declare(strict_types=1);

namespace Nextras\OrmBrickDateTime;

use Brick\DateTime\LocalDate;
use Nextras\Orm\Exception\InvalidArgumentException;
use Nextras\OrmBrickDateTime\Internals\GenericPropertyWrapper;

/**
 * @extends GenericPropertyWrapper<LocalDate>
 */
class LocalDatePropertyWrapper extends GenericPropertyWrapper
{
	public function convertToRawValue(mixed $value): string
	{
		assert($value instanceof LocalDate);
		return $value->toISOString();
	}

	public function convertFromRawValue(mixed $value): LocalDate
	{
		return match (true) {
			is_string($value) => LocalDate::parse($value),
			$value instanceof \DateTimeInterface => LocalDate::of(
				year: (int) $value->format('y'),
				month: (int) $value->format('m'),
				day: (int) $value->format('d'),
			),
			default => throw new InvalidArgumentException(),
		};
	}
}
