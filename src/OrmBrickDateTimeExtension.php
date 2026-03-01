<?php declare(strict_types = 1);

namespace Nextras\OrmBrickDateTime;

use Brick\DateTime\Instant;
use Brick\DateTime\LocalDate;
use Brick\DateTime\LocalDateTime;
use Nextras\Orm\Entity\Reflection\EntityMetadata;
use Nextras\Orm\Entity\Reflection\PropertyMetadata;
use Nextras\Orm\Extension;
use Nextras\Orm\Mapper\Dbal\DbalMapper;
use Nextras\Orm\Mapper\IMapper;
use PHPStan\PhpDocParser\Ast\Type\TypeNode;


class OrmBrickDateTimeExtension extends Extension
{
	public function configureEntityPropertyMetadata(
		EntityMetadata   $entityMetadata,
		PropertyMetadata $propertyMetadata,
		TypeNode         $propertyType,
	): void
	{
		if ($propertyMetadata->wrapper !== null) return;

		if (isset($propertyMetadata->types[Instant::class])) {
			$propertyMetadata->wrapper = InstantPropertyWrapper::class;
		} else if (isset($propertyMetadata->types[LocalDate::class])) {
			$propertyMetadata->wrapper = LocalDatePropertyWrapper::class;
		}
	}

	public function configureMapper(IMapper $mapper): void
	{
		if (!$mapper instanceof DbalMapper) return;

		$conventions = $mapper->getConventions();
		$entityMetadata = $mapper->getRepository()->getEntityMetadata();

		foreach ($entityMetadata->getProperties() as $property) {
			$storageKey = $conventions->convertEntityToStorageKey($property->name);
			match ($property->wrapper) {
				InstantPropertyWrapper::class => $conventions->setModifier($storageKey, '%dt'),
				LocalDateTime::class => $conventions->setModifier($storageKey, '%ldt'),
				LocalDate::class => $conventions->setModifier($storageKey, '%s'),
				default => null, // no-op
			};
		}
	}
}
