<?php
namespace ExtendedPack\Bundle\ArrayConverter\StandardToFlat\Product\ValueConverter;

use Pim\Component\Connector\ArrayConverter\StandardToFlat\Product\ValueConverter\AbstractValueConverter;
use Pim\Component\Connector\ArrayConverter\StandardToFlat\Product\ValueConverter\ValueConverterInterface;

class AccessoriesListConverter extends AbstractValueConverter implements ValueConverterInterface
{
	public function convert($attributeCode, $data)
	{
		$convertedItem = [];
		foreach ($data as $value) {
			$flatName = $this->columnsResolver->resolveFlatAttributeName(
				$attributeCode,
				$value['locale'],
				$value['scope']
            );
            $convertedItem[$flatName] = json_encode($value['data'], JSON_UNESCAPED_UNICODE);
		}
		return $convertedItem;
	}
}