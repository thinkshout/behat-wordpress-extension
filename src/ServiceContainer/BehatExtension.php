<?php
namespace paulgibbs\WordPress\Behat\ServiceContainer;

use Behat\Behat\Context\ServiceContainer\ContextExtension;
    Behat\Testwork\ServiceContainer\Extension as ExtensionInterface,
    Behat\Testwork\ServiceContainer\ExtensionManager;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
    Symfony\Component\DependencyInjection\ContainerBuilder,
    Symfony\Component\DependencyInjection\Definition;

class BehatExtension implements ExtensionInterface
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
