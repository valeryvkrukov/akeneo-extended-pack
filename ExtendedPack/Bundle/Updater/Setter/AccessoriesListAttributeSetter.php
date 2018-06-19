<?php
namespace ExtendedPack\Bundle\Updater\Setter;

use Akeneo\Component\StorageUtils\Exception\InvalidPropertyException;
use Akeneo\Component\StorageUtils\Exception\InvalidPropertyTypeException;
use Pim\Component\Catalog\Builder\EntityWithValuesBuilderInterface;
use Pim\Component\Catalog\Model\AttributeInterface;
use Pim\Component\Catalog\Model\EntityWithValuesInterface;
use Pim\Component\Catalog\Updater\Setter\AbstractAttributeSetter;

class AccessoriesListAttributeSetter extends AbstractAttributeSetter
{
	public function setAttributeData(
        EntityWithValuesInterface $entityWithValues,
        AttributeInterface $attribute,
        $data,
        array $options = []
    ) {
		$options = $this->resolver->resolve($options);
		
		$this->checkData($attribute, $data);
		$values = [];
		foreach ($data as $item) {
			$values[] = [
				'imageUrl' => isset($item['imageUrl'])? $item['imageUrl']: null,
				'title' => $item['title'],
				'description' => isset($item['description'])? $item['description']: null,
				'qty' => isset($item['qty'])? $item['qty']: 0,
			];
		}
		$this->entityWithValuesBuilder->addOrReplaceValue(
			$entityWithValues,
            $attribute,
            $options['locale'],
            $options['scope'],
            $values
		);
    }

    protected function checkData(AttributeInterface $attribute, $data)
    {
    	if (!is_array($data)) {
    		throw InvalidPropertyTypeException::arrayExpected(
                $attribute->getCode(),
                static::class,
                $data
            );
    	}
    	foreach ($data as $item) {
    		if (!is_array($item)) {
                throw InvalidPropertyTypeException::arrayOfArraysExpected(
                    $attribute->getCode(),
                    static::class,
                    $data
                );
            }
            if (!array_key_exists('title', $item)) {
                throw InvalidPropertyTypeException::arrayKeyExpected(
                    $attribute->getCode(),
                    'title',
                    static::class,
                    $data
                );
            }
    	}
    }

    public function supportsAttribute(AttributeInterface $attribute)
    {
    	return true;
    }
}