<?php declare(strict_types=1);

/** @testCase */

namespace NextrasTests\OrmBrickDateTime;

use Brick\DateTime\Instant;
use Nextras\Orm\Entity\Reflection\PropertyMetadata;
use Nextras\Orm\Exception\InvalidArgumentException;
use Nextras\Orm\Exception\NullValueException;
use Nextras\OrmBrickDateTime\InstantPropertyWrapper;
use Tester\Assert;
use Tester\TestCase;

require_once __DIR__ . '/../../bootstrap.php';


class InstantPropertyWrapperTest extends TestCase
{
	private function createWrapper(bool $nullable = false): InstantPropertyWrapper
	{
		$meta = new PropertyMetadata();
		$meta->name = 'testProp';
		$meta->isNullable = $nullable;
		return new InstantPropertyWrapper($meta);
	}


	public function testConvertFromRawValueString(): void
	{
		$wrapper = $this->createWrapper();
		$result = $wrapper->convertFromRawValue('2001-09-09T03:46:40+02:00');
		Assert::type(Instant::class, $result);
		Assert::same(1_000_000_000, $result->getEpochSecond());
	}


	public function testConvertFromRawValueDateTime(): void
	{
		$wrapper = $this->createWrapper();
		$dt = new \DateTimeImmutable('@1000000000');
		$result = $wrapper->convertFromRawValue($dt);
		Assert::type(Instant::class, $result);
		Assert::same(1_000_000_000, $result->getEpochSecond());
	}


	public function testConvertFromRawValueInvalidType(): void
	{
		$wrapper = $this->createWrapper();
		Assert::exception(
			fn() => $wrapper->convertFromRawValue(12345),
			InvalidArgumentException::class,
		);
	}


	public function testConvertToRawValue(): void
	{
		$wrapper = $this->createWrapper();
		$instant = Instant::of(1_000_000_000);
		$result = $wrapper->convertToRawValue($instant);
		Assert::type(\DateTimeImmutable::class, $result);
		Assert::same(1_000_000_000, $result->getTimestamp());
	}


	public function testSetInjectedValueRejectsNonInstant(): void
	{
		$wrapper = $this->createWrapper();
		Assert::exception(
			fn() => $wrapper->setInjectedValue('not-an-instant'),
			InvalidArgumentException::class,
		);
	}


	public function testSetRawValueNullOnNonNullableThrows(): void
	{
		$wrapper = $this->createWrapper(nullable: false);
		Assert::exception(
			fn() => $wrapper->setRawValue(null),
			NullValueException::class,
		);
	}


	public function testSetRawValueNullOnNullable(): void
	{
		$wrapper = $this->createWrapper(nullable: true);
		$wrapper->setRawValue(null);
		Assert::null($wrapper->getInjectedValue());
		Assert::null($wrapper->getRawValue());
	}


	public function testRoundTrip(): void
	{
		$wrapper = $this->createWrapper();
		$instant = Instant::of(1_700_000_000);
		$wrapper->setInjectedValue($instant);
		Assert::same($instant, $wrapper->getInjectedValue());
		$raw = $wrapper->getRawValue();
		Assert::type(\DateTimeImmutable::class, $raw);
		Assert::same(1_700_000_000, $raw->getTimestamp());
	}
}

(new InstantPropertyWrapperTest())->run();
