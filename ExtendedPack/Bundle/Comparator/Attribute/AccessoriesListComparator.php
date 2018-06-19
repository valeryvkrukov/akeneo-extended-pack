<?php
namespace ExtendedPack\Bundle\Comparator\Attribute;

use Pim\Component\Catalog\Comparator\ComparatorInterface;

class AccessoriesListComparator implements ComparatorInterface
{
	protected $types;

	public function __construct(array $types)
	{
		$this->types = $types;
	}

	public function supports($type)
	{
		return in_array($type, $this->types);
	}

	public function compare($data, $originals)
	{
		$default = ['locale' => null, 'scope' => null, 'data' => []];
		$originals = array_merge($default, $originals);
		if ($data['data'] === null) {
			$data['data'] = [];
		}
		if ($data['data'] === $originals['data']) {
			return null;
		}
		return $data;
	}
}