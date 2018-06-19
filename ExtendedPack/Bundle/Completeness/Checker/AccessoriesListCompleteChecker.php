<?php
namespace ExtendedPack\Bundle\Completeness\Checker;

use ExtendedPack\Bundle\AttributeType\AttributeTypes;
use Pim\Component\Catalog\Completeness\Checker\ValueCompleteCheckerInterface;
use Pim\Component\Catalog\Model\ChannelInterface;
use Pim\Component\Catalog\Model\LocaleInterface;
use Pim\Component\Catalog\Model\ValueInterface;

class AccessoriesListCompleteChecker implements ValueCompleteCheckerInterface
{
	public function isComplete(ValueInterface $value, ChannelInterface $channel, LocaleInterface $locale)
	{
    	if ($value->getScope() !== null && $channel !== $value->getScope()) {
    		return false;
    	}
    	if ($value->getLocale() !== null && $locale !== $value->getLocale()) {
    		return false;
    	}
    	$collection = $value->getData();
    	return $collection !== null && count($collection) > 0;
	}

	public function supportsValue(ValueInterface $value, ChannelInterface $channel, LocaleInterface $locale)
	{
		return AttributeTypes::BACKEND_TYPE_ACCESSORIES_LIST === $value->getAttribute()->getBackendType();
	}
}