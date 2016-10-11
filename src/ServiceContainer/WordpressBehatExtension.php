<?php
namespace PaulGibbs\WordpressBehatExtension\ServiceContainer;

use Behat\Behat\Context\ServiceContainer\ContextExtension,
    Behat\Testwork\ServiceContainer\Extension,
    Behat\Testwork\ServiceContainer\ExtensionManager;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition,
    Symfony\Component\DependencyInjection\ContainerBuilder,
    Symfony\Component\DependencyInjection\Definition;

/**
 * WordpressBehatExtension is an integration layer between Behat and WordPress.
 */
class WordpressBehatExtension implements Extension
{
    /**
     * Returns the extension config key.
     *
     * @return string
     */
    public function getConfigKey()
    {
        return 'wordpress';
    }

    /**
     * Initializes extension.
     *
     * @param ExtensionManager $extensionManager
     */
    public function initialize(ExtensionManager $extensionManager)
    {
    }

    /**
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
    }

    /**
     * Set up configuration for the extension.
     *
     * @param ArrayNodeDefinition $builder
     */
    public function configure(ArrayNodeDefinition $builder)
    {
        $builder
            ->addDefaultsIfNotSet()
                ->children()
                    ->scalarNode('path')
                    ->end()
                    ->scalarNode('driver')
                        ->defaultValue('wp-cli')
                    ->end()
                ->end()
            ->end();
    }

    /**
     * Loads extension services into temporary container.
     *
     * @param ContainerBuilder $container
     * @param array            $config
     */
    public function load(ContainerBuilder $container, array $config)
    {
        $definition = new Definition(
            'PaulGibbs\WordpressBehatExtension\Context\Initializer\WordpressAwareInitializer',
            array('%wordpress.parameters%')
        );
        $definition->addTag(ContextExtension::INITIALIZER_TAG, array('priority' => 0));

        $container->setDefinition('PaulGibbs.wordpress.initializer', $definition);
        $container->setParameter('wordpress.parameters', $config);
    }
}
