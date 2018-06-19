<?php
namespace ExtendedPack\Bundle\AttributeType;

use Pim\Bundle\CatalogBundle\AttributeType\AbstractAttributeType;

class AccessoriesListType extends AbstractAttributeType
{
	public function getName()
	{
		return AttributeTypes::ACCESSORIES_LIST;
	}
}