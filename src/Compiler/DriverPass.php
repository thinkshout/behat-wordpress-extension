<?php
namespace PaulGibbs\WordpressBehatExtension\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder,
    Symfony\Component\DependencyInjection\Reference,
    Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;

/**
 * WordpressBehatExtension container compilation pass.
 */
class DriverPass implements CompilerPassInterface
{
    /**
     * Modify the container before Symfony compiles it.
     *
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        $wordpress = $container->getDefinition('wordpress.wordpress');
        if (! $wordpress) {
            return;
        }

        foreach ($container->findTaggedServiceIds('wordpress.driver') as $id => $attributes) {
            foreach ($attributes as $attribute) {
                if (isset($attribute['alias']) && $name = $attribute['alias']) {
                    $wordpress->addMethodCall(
                        'registerDriver', array($name, new Reference($id))
                    );
                }
            }
        }

        $wordpress->addMethodCall(
            'setDefaultDriverName', array($container->getParameter('wordpress.wordpress.default_driver'))
        );
    }
}
