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
                    ->scalarNode('path')
                        ->defaultValue('/srv/www/buddypress.dev/src')
                    ->end()
                    ->scalarNode('url')
                        ->defaultValue('localhost')
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
        $definition = new Definition(
            'PaulGibbs\WordpressBehatExtension\Context\Initializer\WordpressContextInitializer',
            array(
                '%wordpress.parameters%',
                '%mink.parameters%',
                '%paths.base%',
            )
        );
        $definition->addTag(ContextExtension::INITIALIZER_TAG, array('priority' => 0));

        $container->setDefinition('PaulGibbs.wordpress.initializer', $definition);
        $container->setParameter('wordpress.parameters', $config);
    }
}
