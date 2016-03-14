<?php
namespace PaulGibbs\WordPress\Behat\ServiceContainer;

use Behat\Behat\Context\ServiceContainer\ContextExtension;
use Behat\Testwork\ServiceContainer\Extension as ExtensionInterface;
use Behat\Testwork\ServiceContainer\ExtensionManager;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

class WordPressExtension implements ExtensionInterface
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
                ->arrayNode('database')
                    ->children()
                        ->scalarNode('name')
                            ->defaultValue('wordpress')
                        ->end()
                        ->scalarNode('user_name')
                            ->defaultValue('root')
                        ->end()
                        ->scalarNode('user_password')
                            ->defaultValue('')
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('wordpress')
                    ->children()
                        ->scalarNode('user_name')
                            ->defaultValue('admin')
                        ->end()
                        ->scalarNode('app_password')
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
        $definition = new Definition('PaulGibbs\WordPress\Behat\Context\Initializer\WordPressContextInitializer', array(
            '%wordpress.parameters%',
            '%mink.parameters%',
            '%paths.base%',
        ));
        $definition->addTag(ContextExtension::INITIALIZER_TAG, array('priority' => 0));
        $container->setDefinition('PaulGibbs.wordpress.context_initializer', $definition);

        // Other options.
        $container->setParameter('wordpress.parameters', $config);
    }
}
