<?php
namespace ExtendedPack\Bundle\Normalizer\Standard;

use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use ExtendedPack\Bundle\Model\AccessoriesListItem;

class AccessoriesListItemNormalizer implements NormalizerInterface
{
	public function normalize($object, $format = null, array $context = [])
	{
		return [
			'imageUrl' => $object->getImageUrl(),
			'title' => $object->getTitle(),
			'description' => $object->getDescription(),
			'qty' => $object->getQty(),
        ];
	}

	public function supportsNormalization($data, $format = null)
	{
		return $data instanceof AccessoriesListItem && 'standard' === $format;
	}
}