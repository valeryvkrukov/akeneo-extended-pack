<?php
namespace ExtendedPack\Bundle\Normalizer\Storage;

use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use ExtendedPack\Bundle\Model\AccessoriesListItem;

class AccessoriesListItemNormalizer implements NormalizerInterface
{
	protected $normalizer;

	public function __construct(NormalizerInterface $normalizer)
	{
		$this->normalizer = $normalizer;
	}

	public function normalize($object, $format = null, array $context = [])
	{
		return $this->normalizer->normalize($object, $format, $context);
	}

	public function supportsNormalization($data, $format = null)
	{
		return $data instanceof AccessoriesListItem && 'storage' === $format;
	}
}