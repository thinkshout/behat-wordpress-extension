<?php
namespace PaulGibbs\WordpressBehatExtension\ServiceContainer;

use Behat\Behat\Context\ServiceContainer\ContextExtension;
use Behat\Testwork\ServiceContainer\Extension as ExtensionInterface;
use Behat\Testwork\ServiceContainer\ExtensionManager;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

class WordpressBehatExtension implements ExtensionInterface
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
                    ->scalarNode('admin_username')
                        ->defaultValue('wpbehat')
                    ->end()
                    ->scalarNode('admin_password')
                        ->defaultValue('wpbehat')
                    ->end()
                    ->scalarNode('url')
                        ->defaultValue('')
                    ->end()
                    ->scalarNode('path')  // Relative
                        ->defaultValue('../../../../..')  // wp-content/plugins/<plugin>/tests/behat
                    ->end()


                    // Optional - automatically set from wp-config-tests.php if missing.
                    ->scalarNode('db_host')
                        ->defaultValue('')
                    ->end()
                    ->scalarNode('db_name')
                        ->defaultValue('')
                    ->end()
                    ->scalarNode('db_username')
                        ->defaultValue('')
                    ->end()
                    ->scalarNode('db_password')
                        ->defaultValue('')
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
        $definition = new Definition('PaulGibbs\WordpressBehatExtension\Context\Initializer\WordpressContextInitializer', array(
            '%wordpress.parameters%',
            '%mink.parameters%',
            '%paths.base%',
        ));
        $definition->addTag(ContextExtension::INITIALIZER_TAG, array('priority' => 0));
        $container->setDefinition('PaulGibbs.wordpress.initializer', $definition);

        // Other options.
        $container->setParameter('wordpress.parameters', $config);
    }
}
