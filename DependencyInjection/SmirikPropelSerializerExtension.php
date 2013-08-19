<?php

namespace Smirik\PropelSerializerBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class SmirikPropelSerializerExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);
            
		$options = array('bundles');
		foreach ($options as $option)
		{
			if (!isset($config[$option]) || $config[$option] == '') {
			  throw new \InvalidArgumentException('The '.$option.' option must be set');
			}
		}

		$container->setParameter('propel_serializer', array(
			'bundles'    => $config['bundles'],
		));
        
        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');
    }
}
