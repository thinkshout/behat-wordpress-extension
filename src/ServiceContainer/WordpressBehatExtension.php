<?php
namespace PaulGibbs\WordpressBehatExtension\ServiceContainer;

use Behat\Behat\Context\ServiceContainer\ContextExtension;
use Behat\Testwork\ServiceContainer\Extension as ExtensionInterface;
use Behat\Testwork\ServiceContainer\ExtensionManager;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

/**
 * WordpressBehatExtension is an integration layer between Behat and WordPress.
 */
class WordpressBehatExtension implements ExtensionInterface
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
     * This method is called immediately after all extensions are activated but
     * before any extension's `configure()` method is called. This allows extensions
     * to hook into the configuration of other extensions providing such an
     * extension point.
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
                        ->defaultValue('/srv/www/buddypress.dev/src')
                    ->end()
                    ->scalarNode('url')
                        ->defaultValue('localhost')
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

        // Set up WordPress.
        $this->installWordpress( $config );
    }

    /**
     * Install WordPress.
     *
     * @param array $config
     */
    protected function installWordpress(array $config)
    {
        $cmd = sprintf(
            'wp --path=%s --url=%s core is-installed',
            escapeshellarg($config['path']),
            escapeshellarg($config['url'])
        );
        exec($cmd, $cmd_output, $exit_code);

        if ($exit_code === 0) {
            // This means WordPress is installed. Let's remove it.
            $cmd = sprintf(
                'wp --path=%s --url=%s db reset --yes',
                escapeshellarg($config['path']),
                escapeshellarg($config['url'])
            );
            exec($cmd);
        }

        $cmd = sprintf(
            'wp --path=%s --url=%s core install --title=%s --admin_user=%s --admin_password=%s --admin_email=%s --skip-email',
            escapeshellarg($config['path']),
            escapeshellarg($config['url']),
            escapeshellarg('Test Site'),
            escapeshellarg('admin'),
            escapeshellarg('admin'),
            escapeshellarg('admin@example.com')
        );
        exec($cmd, $cmd_output);

        if ($cmd_output[0] !== 'Success: WordPress installed successfully.') {
            throw new \Exception('Error installing WordPress: ' . implode( PHP_EOL, $cmd_output ) );
            die;
        }
    }
}
