<?php
namespace paulgibbs\WordPress\Behat\ServiceContainer;

use Behat\Behat\Context\ServiceContainer\ContextExtension;
use Behat\Testwork\ServiceContainer\Extension as ExtensionInterface;
use Behat\Testwork\ServiceContainer\ExtensionManager;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

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
                        ->scalarNode('host')
                            ->defaultValue('localhost')
                        ->end()
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
        // Register a Context Initializer service for Behat.
        $definition = new Definition('paulgibbs\WordPress\Behat\Context\Initializer\WordPressContextInitializer', array(
            '%wordpress.parameters%',
            '%mink.parameters%',
            '%paths.base%',
        ));
        $definition->addTag(ContextExtension::INITIALIZER_TAG, array('priority' => 0));
        $container->setDefinition('paulgibbs.wordpress.context_initializer', $definition);

        // Other options.
        $container->setParameter('wordpress.parameters', $config);
    }
}
