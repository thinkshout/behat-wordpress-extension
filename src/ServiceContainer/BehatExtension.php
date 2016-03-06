<?php
namespace paulgibbs\WordPress\Behat\ServiceContainer;

use Behat\Behat\Context\ServiceContainer\ContextExtension;
use Behat\Testwork\ServiceContainer\Extension;
use Behat\Testwork\ServiceContainer\ExtensionManager;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;

class BehatExtension implements Extension
{
	public function getConfigKey()
	{
		return 'wordpress';
	}

	public function configure(ArrayNodeDefinition $builder)
	{
		$builder
			->addDefaultsIfNotSet()
			->children()
				->scalarNode('path')
					->defaultValue('vendor')
				->end()
				->arrayNode('connection')
					->children()
						->scalarNode('db')
							->defaultValue('wordpress')
						->end()
						->scalarNode('username')
							->defaultValue('root')
						->end()
						->scalarNode('password')
							->defaultValue('')
						->end()
					->end()
				->end()
			->end();
	}

	public function initialize(ExtensionManager $extensionManager)
	{
	}

	public function process(ContainerBuilder $container)
	{
	}

	public function load(ContainerBuilder $container, array $config)
	{
		$this->loadContextInitializer($container);
		$container->setParameter('wordpress.parameters', $config);
	}

	/**
	 * Register a Context Initializer service for Behat.
	 *
	 * @param ContainerBuilder $container
	 */
	private function loadContextInitializer(ContainerBuilder $container)
	{
		$definition = new Definition('paulgibbs\WordPress\Behat\Context\Initializer', array(
			'%wordpress.parameters%',
			'%mink.parameters%',
			'%paths.base%',
		));

		$definition->addTag(ContextExtension::INITIALIZER_TAG, array('priority' => 0));
		$container->setDefinition('paulgibbs.wordpress.context_initializer', $definition);
	}
}
