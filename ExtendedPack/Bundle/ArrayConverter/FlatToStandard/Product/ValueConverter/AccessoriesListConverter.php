<?php
namespace ExtendedPack\Bundle\ArrayConverter\FlatToStandard\Product\ValueConverter;

use Pim\Component\Connector\ArrayConverter\FlatToStandard\Product\ValueConverter\ValueConverterInterface;

class AccessoriesListConverter implements ValueConverterInterface
{
	protected $supportedFieldTypes;

	public function __construct(array $supportedFieldTypes)
	{
		$this->supportedFieldTypes = $supportedFieldTypes;
	}

	public function supportsField($attributeType)
	{
		return in_array($attributeType, $this->supportedFieldTypes);
	}

	public function convert(array $attributeFieldInfo, $value)
	{
		if (trim($value) === '') {
            return [];
        }
        return [
        	$attributeFieldInfo['attribute']->getCode() => [[
        		'locale' => $attributeFieldInfo['locale_code'],
        		'scope' => $attributeFieldInfo['scope_code'],
        		'data' => json_decode($value, true),
        	]],
        ];
	}
}