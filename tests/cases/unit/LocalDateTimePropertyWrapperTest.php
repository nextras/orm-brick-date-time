<?php declare(strict_types=1);

/** @testCase */

namespace NextrasTests\OrmBrickDateTime;

use Brick\DateTime\LocalDateTime;
use Nextras\Orm\Entity\Reflection\PropertyMetadata;
use Nextras\OrmBrickDateTime\LocalDateTimePropertyWrapper;
use Tester\Assert;
use Tester\TestCase;

require_once __DIR__ . '/../../bootstrap.php';


class LocalDateTimePropertyWrapperTest extends TestCase
{
	private function createWrapper(): LocalDateTimePropertyWrapper
	{
		$meta = new PropertyMetadata();
		$meta->name = 'testProp';
		$meta->isNullable = false;
		return new LocalDateTimePropertyWrapper($meta);
	}


	public function testConvertFromRawValueString(): void
	{
		$wrapper = $this->createWrapper();
		$result = $wrapper->convertFromRawValue('2024-06-15T12:30:45');
		Assert::type(LocalDateTime::class, $result);
		Assert::same('2024-06-15T12:30:45', (string) $result);
	}


	public function testConvertToRawValue(): void
	{
		$wrapper = $this->createWrapper();
		$dt = LocalDateTime::of(2024, 6, 15, 12, 30, 45);
		$result = $wrapper->convertToRawValue($dt);
		Assert::type(\DateTimeImmutable::class, $result);
		Assert::same('2024-06-15 12:30:45', $result->format('Y-m-d H:i:s'));
	}


	public function testRoundTrip(): void
	{
		$wrapper = $this->createWrapper();
		$dt = LocalDateTime::of(2000, 12, 31, 23, 59, 59);
		$wrapper->setRawValue('2000-12-31T23:59:59');
		Assert::equal($dt, $wrapper->getInjectedValue());
		$raw = $wrapper->getRawValue();
		Assert::type(\DateTimeImmutable::class, $raw);
		Assert::same('2000-12-31 23:59:59', $raw->format('Y-m-d H:i:s'));
	}
}

(new LocalDateTimePropertyWrapperTest())->run();
