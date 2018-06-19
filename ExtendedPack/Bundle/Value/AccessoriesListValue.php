<?php
namespace ExtendedPack\Bundle\Value;

use Pim\Component\Catalog\Model\AbstractValue;
use Pim\Component\Catalog\Model\AttributeInterface;
use Pim\Component\Catalog\Model\ValueInterface;
use ExtendedPack\Bundle\Model\AccessoriesList;

class AccessoriesListValue extends AbstractValue implements ValueInterface
{
	protected $data;

	public function __construct(AttributeInterface $attribute, $channel, $locale, AccessoriesList $data = null)
	{
		$this->setAttribute($attribute);
        $this->setScope($channel);
        $this->setLocale($locale);
        $this->data = $data;
	}

	public function getData()
	{
		return $this->data;
	}

	public function hasData()
	{
		if (!is_array($this->data) || is_null($this->data)) {
			return false;
		}
		if (count($this->data) > 0) {
			return true;
		}
		return false;
	}

	public function __toString()
	{
		$items = [];
		foreach ($this->data as $key => $item) {
			$items[] = $item->getTitle();
		}
		return implode(', ', $items);
	}
}
