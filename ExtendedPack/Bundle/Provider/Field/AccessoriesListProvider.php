<?php
namespace ExtendedPack\Bundle\Provider\Field;

use Pim\Component\Catalog\Model\AttributeInterface;
use Pim\Bundle\EnrichBundle\Provider\Field\FieldProviderInterface;
use ExtendedPack\Bundle\AttributeType\AttributeTypes;

class AccessoriesListProvider implements FieldProviderInterface
{
	public function getField($element)
	{
		return AttributeTypes::ACCESSORIES_LIST;
	}

	public function supports($element)
	{
		return $element instanceof AttributeInterface && AttributeTypes::ACCESSORIES_LIST === $element->getType();
	}
}