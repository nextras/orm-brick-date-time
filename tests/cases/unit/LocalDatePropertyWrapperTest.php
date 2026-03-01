<?php declare(strict_types=1);

/** @testCase */

namespace NextrasTests\OrmBrickDateTime;

use Brick\DateTime\LocalDate;
use Nextras\Orm\Entity\Reflection\PropertyMetadata;
use Nextras\OrmBrickDateTime\LocalDatePropertyWrapper;
use Tester\Assert;
use Tester\TestCase;

require_once __DIR__ . '/../../bootstrap.php';


class LocalDatePropertyWrapperTest extends TestCase
{
	private function createWrapper(): LocalDatePropertyWrapper
	{
		$meta = new PropertyMetadata();
		$meta->name = 'testProp';
		$meta->isNullable = false;
		return new LocalDatePropertyWrapper($meta);
	}


	public function testConvertFromRawValueString(): void
	{
		$wrapper = $this->createWrapper();
		$result = $wrapper->convertFromRawValue('2024-06-15');
		Assert::type(LocalDate::class, $result);
		Assert::same('2024-06-15', (string) $result);
	}


	public function testConvertToRawValue(): void
	{
		$wrapper = $this->createWrapper();
		$date = LocalDate::of(2024, 6, 15);
		$result = $wrapper->convertToRawValue($date);
		Assert::same('2024-06-15', $result);
	}


	public function testRoundTrip(): void
	{
		$wrapper = $this->createWrapper();
		$date = LocalDate::of(2000, 1, 1);
		$wrapper->setRawValue('2000-01-01');
		Assert::equal($date, $wrapper->getInjectedValue());
		Assert::same('2000-01-01', $wrapper->getRawValue());
	}
}

(new LocalDatePropertyWrapperTest())->run();
