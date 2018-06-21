<?php
namespace ExtendedPack\Bundle\Factory\Value;

use Akeneo\Component\StorageUtils\Exception\InvalidPropertyException;
use Akeneo\Component\StorageUtils\Exception\InvalidPropertyTypeException;
use Akeneo\Component\StorageUtils\Repository\IdentifiableObjectRepositoryInterface;
use Akeneo\Component\FileStorage\Model\FileInfoInterface;
use Akeneo\Component\FileStorage\Repository\FileInfoRepositoryInterface;
use Doctrine\Common\Collections\Collection;
use Pim\Component\Catalog\Model\AttributeInterface;
use Pim\Component\Catalog\Factory\Value\ValueFactoryInterface;

class AccessoriesListValueFactory implements ValueFactoryInterface
{
	protected $accessoriesListClass;
	protected $accessoriesListItemClass;
	protected $productValueClass;
	protected $supportedAttributeType;

	public function __construct(
		$accessoriesListClass,
		$accessoriesListItemClass,
		$productValueClass,
		$supportedAttributeType
	) {
        $this->accessoriesListClass = $accessoriesListClass;
        $this->accessoriesListItemClass = $accessoriesListItemClass;
        $this->productValueClass = $productValueClass;
        $this->supportedAttributeType = $supportedAttributeType;
    }

    public function create(AttributeInterface $attribute, $channelCode, $localeCode, $data)
    {
    	$this->checkData($attribute, $data);
    	if ($data === null) {
            $data = [];
        }
        $value = new $this->productValueClass(
        	$attribute,
        	$channelCode,
        	$localeCode,
        	$this->createList($attribute, $data)
        );
        return $value;
    }

    protected function createList(AttributeInterface $attribute, array $data)
    {
    	$list = new $this->accessoriesListClass();
    	foreach ($data as $item) {
    		try {
    			$listItem = new $this->accessoriesListItemClass();
                $listItem->setProductId((isset($item['productId'])? $item['productId']: null));
                $listItem->setImageUrl((isset($item['imageUrl'])? $item['imageUrl']: null));
    			$listItem->setTitle($item['title']);
                $listItem->setDescription((isset($item['description'])? $item['description']: null));
                $listItem->setQty((isset($item['qty'])? $item['qty']: 0));
    			$list->add($listItem);
    		} catch(InvalidPropertyException $e) {
    			throw InvalidPropertyException::expectedFromPreviousException($attribute->getCode(), self::class, $e);
    		}
    	}
    	return $list;
    }

    public function supports($attributeType)
    {
    	return $attributeType === $this->supportedAttributeType;
    }

    protected function checkData(AttributeInterface $attribute, $data)
    {
    	if ($data === null || $data === []) {
    		return;
        }
        if (!is_array($data)) {
        	throw InvalidPropertyTypeException::arrayExpected($attribute->getCode(), static::class, $data);
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
}