<?php
namespace ExtendedPack\Bundle\Normalizer\Flat;

use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Pim\Bundle\VersioningBundle\Normalizer\Flat\AbstractValueDataNormalizer;
use ExtendedPack\Bundle\Model\AccessoriesListItem;

class AccessoriesListItemNormalizer extends AbstractValueDataNormalizer
{
	protected $normalizer;
	protected $supportedFormats = ['flat'];

	public function __construct(NormalizerInterface $normalizer)
	{
		$this->normalizer = $normalizer;
	}

	public function supportsNormalization($data, $format = null)
	{
		return $data instanceof AccessoriesListItem && in_array($format, $this->supportedFormats);
	}

	protected function doNormalize($object, $format = null, array $context = [])
	{
		return json_encode($this->normalizer->normalize($object, $format, $context), JSON_UNESCAPED_UNICODE);
	}
}