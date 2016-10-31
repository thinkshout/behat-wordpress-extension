<?php
namespace PaulGibbs\WordpressBehatExtension\ServiceContainer;

use Behat\Behat\Context\ServiceContainer\ContextExtension,
    Behat\Testwork\ServiceContainer\Extension as ExtensionInterface,
    Behat\Testwork\ServiceContainer\ExtensionManager,
    Behat\Testwork\ServiceContainer\ServiceProcessor;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition,
    Symfony\Component\Config\FileLocator,
    Symfony\Component\DependencyInjection\ContainerBuilder,
    Symfony\Component\DependencyInjection\Definition,
    Symfony\Component\DependencyInjection\Loader\FileLoader,
    Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

use PaulGibbs\WordpressBehatExtension\Compiler\DriverPass;


/**
 * Behat extension for WordPress configuration and loading.
 */
class WordpressBehatExtension implements ExtensionInterface
{
    /**
     * @var ServiceProcessor
     */
    protected $processor;

    /**
     * Constructor.
     *
     * @param ServiceProcessor|null $processor Optional.
     */
    public function __construct(ServiceProcessor $processor = null)
    {
        $this->processor = $processor ?: new ServiceProcessor();
    }

    /**
     * Returns the extension config key.
     *
     * @return string
     */
    public function getConfigKey() {
        return 'wordpress';
    }

    /**
     * Initialise extension.
     *
     * This method is called immediately after all extensions are activated but
     * before any extension `configure()` method is called. This allows extensions
     * to hook into the configuration of other extensions providing such an
     * extension point.
     *
     * @param ExtensionManager $extension_manager
     */
    public function initialize(ExtensionManager $extension_manager)
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
            ->children()
                ->scalarNode('default_driver')
                    ->defaultValue('wpcli')
                ->end()
                ->arrayNode('wpcli')
                    ->addDefaultsIfNotSet()
                        ->children()
                            ->scalarNode('alias')->end()
                            ->scalarNode('path')->end()
                            ->scalarNode('url')->end()
                        ->end()
                    ->end()
                ->end()
            ->end();
    }

    /**
     * Load extension services into temporary container.
     *
     * @param ContainerBuilder $container
     * @param array            $config
     */
    public function load(ContainerBuilder $container, array $config)
    {
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/config'));
        $loader->load('services.yml');

        $container->setParameter('wordpress.wordpress.default_driver', $config['default_driver']);
        $container->setParameter('wordpress.parameters', $config);

        $this->setupWpcliDriver($loader, $container, $config);
    }

    /**
     * Loads settings for the WP-CLI driver.
     *
     * @param FileLoader       $loader
     * @param ContainerBuilder $container
     * @param array            $config
     */
    protected function setupWpcliDriver(FileLoader $loader, ContainerBuilder $container, array $config)
    {
        if (empty($config['wpcli'])) {
            return;
        }

        $loader->load('drivers/wpcli.yml');

        if (empty($config['wpcli']['alias']) && empty($config['wpcli']['path'])) {
            throw new \RuntimeException('WP-CLI driver requires an `alias` or `root` path.');
        }

        $config['wpcli']['alias'] = isset($config['wpcli']['alias']) ? $config['wpcli']['alias'] : '';
        $container->setParameter('wordpress.driver.wpcli.alias', $config['wpcli']['alias']);

        $config['wpcli']['path'] = isset($config['wpcli']['path']) ? $config['wpcli']['path'] : '';
        $container->setParameter('wordpress.driver.wpcli.path', $config['wpcli']['path']);

        $config['wpcli']['url'] = isset($config['wpcli']['url']) ? $config['wpcli']['url'] : '';
        $container->setParameter('wordpress.driver.wpcli.url', $config['wpcli']['url']);
    }

    /**
     * Modify the container before Symfony compiles it.
     *
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        $driver = new DriverPass();
        $driver->process($container);
    }
}
