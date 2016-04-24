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
                    // Optional - path to this project's Composer's `bin-dir`.
                    ->scalarNode('composer_bin_dir')
                        ->defaultValue(__DIR__.'../../bin/')
                    ->end()

                    // Optional - automatically fetched from wp-config-tests.php if unset.
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

                    // Optional - not fully supported, but implemented for possible future integration.
                    ->scalarNode('site_url')
                        ->defaultValue('http://127.0.0.1')
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
