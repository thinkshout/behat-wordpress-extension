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
        $binDir = explode('/', __DIR__);
        array_pop($binDir);
        array_pop($binDir);
        array_push($binDir, 'bin');
        $binDir = implode('/', $binDir);

        $builder
            ->addDefaultsIfNotSet()
                ->children()
                    // Optional - path to this project's Composer's `bin-dir`.
                    ->scalarNode('composer_bin_dir')
                        ->defaultValue($binDir)
                    ->end()

                    // Optional - set in behat.local.yml.
                    ->scalarNode('site_url')
                        ->defaultValue('http://127.0.0.1:6680')
                    ->end()
                    ->scalarNode('ssh_host')
                        ->defaultValue('127.0.0.1')
                    ->end()
                    ->scalarNode('ssh_username')
                        ->defaultValue('vagrant')
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
