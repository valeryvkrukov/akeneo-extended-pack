<?php
namespace ExtendedPack\Bundle\Normalizer\Standard;

use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use ExtendedPack\Bundle\Model\AccessoriesListItem;

class AccessoriesListItemNormalizer implements NormalizerInterface
{
	protected $container;

	public function __construct(ContainerInterface $container)
	{
		$this->container = $container;
	}

	public function normalize($object, $format = null, array $context = [])
	{
		$item = [
			'productId' => $object->getProductId(),
			'qty' => $object->getQty(),
		];
		return array_merge($item, $this->getListItemDetails($object));
	}

	public function supportsNormalization($data, $format = null)
	{
		return $data instanceof AccessoriesListItem && 'standard' === $format;
	}

	protected function getListItemDetails($object)
	{
		$item = [
			'imageUrl' => null,
			'title' => '',
			'description' => null,
		];
		try {
			$pqbFactory = $this->container->get('pim_catalog.query.product_query_builder_factory');
			$pqb = $pqbFactory->create(['default_locale' => 'en_US', 'default_scope' => 'ecommerce']);
			$pqb->addFilter('id', '=', (string)$object->getProductId());
			$productCursor = $pqb->execute();
			$product = $productCursor->current();
			if ($product) {
				$item['imageUrl'] = $product->getImage() !== null? $product->getImage()->__toString(): null;
				$item['title'] = $product->getValue('sku', null, null) !== null? $product->getValue('sku', null, null)->__toString(): null;
				foreach ($product->getValues() as $value) {
					if ($value->getAttribute()->getCode() === 'description' && $value->getScope() === 'ecommerce') {
						$item['description'] = $value->__toString();
					}
				}
			}
		} catch(Exception $e) {
			//return $item;
		}
		return $item;
	}
}