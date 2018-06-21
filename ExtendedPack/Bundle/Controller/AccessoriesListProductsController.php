<?php
namespace ExtendedPack\Bundle\Controller;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Serializer;

class AccessoriesListProductsController
{
	protected $container;
	protected $maxResults = 20;

	public function __construct(ContainerInterface $container)
	{
		$this->container = $container;
	}

	public function searchAction(Request $request)
	{
		$response = [];
		if ($request->isXmlHttpRequest()) {
			if ($request->get('search')) {
				$search = trim($request->get('search'));
				if (strlen($search) > 0) {
					$limit = $request->get('limit') != null? intval($request->get('limit')): $this->maxResults;
					$offset = $request->get('page') != null? intval($request->get('page')) * $limit: 0;
					$pqbFactory = $this->container->get('pim_catalog.query.product_query_builder_factory');
					$pqb = $pqbFactory->create(['default_locale' => 'en_US', 'default_scope' => 'ecommerce']);
					$pqb->addFilter('name', 'STARTS WITH', $search);
					$productsCursor = $pqb->execute();
					$response['results'] = [];
					foreach ($productsCursor as $product) {
						$response['results'][] = [
							'id' => $product->getId(),
							'text' => $product->getValue('name', null, null)->__toString(),
						];
					}
				}
			}
			
		}
		return $this->sendJsonResponse($response);
	}

	public function loadAction(Request $request, $id)
	{
		$response = [];
		if ($request->isXmlHttpRequest()) {
			$pqbFactory = $this->container->get('pim_catalog.query.product_query_builder_factory');
			$pqb = $pqbFactory->create(['default_locale' => 'en_US', 'default_scope' => 'ecommerce']);
			$pqb->addFilter('id', '=', $id);
			$productCursor = $pqb->execute();
			$product = $productCursor->current();
			$attributeCodes = $product->getUsedAttributeCodes();
			$productModel = [
				'title' => 'name', 
				'description' => 'description',
			];
			foreach ($productModel as $respCode => $attrCode) {
				if (in_array($attrCode, $attributeCodes)) {
					$response['product'][$respCode] = $product->getValue($attrCode, null, null) !== null? $product->getValue($attrCode, null, null)->__toString(): null;
				} else {
					$response['product'][$respCode] = null;
				}
			}
			foreach ($product->getValues() as $value) {
				if ($value->getAttribute()->getCode() === 'description' && $value->getScope() === 'ecommerce') {
					$response['product']['description'] = $value->__toString();
				}
			}
			$response['product']['id'] = $product->getId();
			$response['product']['imageUrl'] = $product->getImage() !== null? $product->getImage()->__toString(): null;
		}
		return $this->sendJsonResponse($response);
	}

	protected function sendJsonResponse($response)
	{
	    $encoders = array(new JsonEncoder());
	    $normalizers = array(new GetSetMethodNormalizer());
	    $serializer = new Serializer($normalizers, $encoders);
	    $response = new Response($serializer->serialize($response, 'json')); 
	    $response->headers->set('Content-Type', 'application/json');
	    return $response;
	}
}