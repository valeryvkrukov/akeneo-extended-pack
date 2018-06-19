<?php
namespace ExtendedPack\Bundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class ExtendedPackExtension extends Extension
{
	public function load(array $configs, ContainerBuilder $container)
	{
		$loader = new YamlFileLoader($container, new FileLocator(__DIR__ .'/../Resources/config'));
		$loader->load('array_converters.yml');
		$loader->load('controllers.yml');
		$loader->load('providers.yml');
		$loader->load('entities.yml');
		$loader->load('models.yml');
		$loader->load('attribute_types.yml');
		$loader->load('comparators.yml');
		$loader->load('completeness.yml');
		$loader->load('factories.yml');
		$loader->load('serializers_indexing.yml');
        $loader->load('serializers_standard.yml');
        $loader->load('serializers_storage.yml');
        $loader->load('serializers_flat.yml');
        $loader->load('updaters.yml');
	}
}