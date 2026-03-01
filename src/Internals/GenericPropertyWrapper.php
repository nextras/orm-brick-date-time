<?php declare(strict_types=1);

namespace Nextras\OrmBrickDateTime\Internals;

use Nextras\Orm\Entity\IPropertyContainer;
use Nextras\Orm\Entity\Reflection\PropertyMetadata;
use Nextras\Orm\Exception\NullValueException;

/**
 * @template T
 */
abstract class GenericPropertyWrapper implements IPropertyContainer
{
	/** @var T|null */
	protected mixed $value;


	public function __construct(
		protected readonly PropertyMetadata $propertyMetadata,
	)
	{
	}

	public function setRawValue($value): void
	{
		if ($value === null) {
			if (!$this->propertyMetadata->isNullable) {
				throw new NullValueException($this->propertyMetadata);
			}
			$this->value = null;
		} else {
			$this->value = $this->convertFromRawValue($value);
		}
	}

	public function getRawValue()
	{
		if ($this->value === null) {
			return null;
		} else {
			return $this->convertToRawValue($this->value);
		}
	}

	public function &getInjectedValue()
	{
		return $this->value;
	}

	public function setInjectedValue($value): bool
	{
		if ($value === null && !$this->propertyMetadata->isNullable) {
			throw new NullValueException($this->propertyMetadata);
		}
		$this->value = $value;
		return true;
	}

	public function hasInjectedValue(): bool
	{
		return $this->value !== null;
	}

	/**
	 * Converts passed value from runtime value to storage representation.
	 * @param T $value
	 */
	abstract public function convertToRawValue(mixed $value): mixed;

	/**
	 * Converts passed value from storage representation to runtime representation.
	 * Conversion must not require the holding entity instance.
	 * @return T
	 */
	abstract public function convertFromRawValue(mixed $value): mixed;
}
