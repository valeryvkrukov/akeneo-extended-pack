<?php
namespace ExtendedPack\Bundle\Normalizer\Indexing\Value;

use Pim\Component\Catalog\Model\ValueInterface;
use Pim\Component\Catalog\Normalizer\Indexing\Product\ProductNormalizer;
use Pim\Component\Catalog\Normalizer\Indexing\ProductAndProductModel;
use Pim\Component\Catalog\Normalizer\Indexing\ProductModel;
use Pim\Component\Catalog\Normalizer\Indexing\Value\AbstractProductValueNormalizer;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use ExtendedPack\Bundle\Value\AccessoriesListValue;

class AccessoriesListNormalizer extends AbstractProductValueNormalizer implements NormalizerInterface
{
	public function supportsNormalization($data, $format = null)
	{
		return $data instanceof AccessoriesListValue && (
			$format === ProductNormalizer::INDEXING_FORMAT_PRODUCT_INDEX ||
			$format === ProductModel\ProductModelNormalizer::INDEXING_FORMAT_PRODUCT_MODEL_INDEX ||
			$format === ProductAndProductModel\ProductModelNormalizer::INDEXING_FORMAT_PRODUCT_AND_MODEL_INDEX
		);
	}

	protected function getNormalizedData(ValueInterface $value)
	{
		$indexedCollection = [];
		$collection = $value->getData();
		if ($collection=== null) {
            return null;
        }
        foreach ($collection as $item) {
        	$indexedCollection[] = [
        		'imageUrl' => $item->getImageUrl(),
				'title' => $item->getTitle(),
				'description' => $item->getDescription(),
				'qty' => $item->getQty(),
        	];
        }
        return $indexedCollection;
	}
}