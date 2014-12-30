<?php
/**
 * File SerializerPass.php
 * Created at: 2014-12-30 09-15
 *
 * @author Daniel Bojdo <daniel.bojdo@web-it.eu>
 */

namespace Webit\Bundle\ShipmentBundle\DependencyInjection\Compiler;


use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class SerializerPass implements CompilerPassInterface
{
    /**
     * You can modify the container here before it is dumped to PHP code.
     *
     * @param ContainerBuilder $container
     *
     * @api
     */
    public function process(ContainerBuilder $container)
    {
        if ($container->hasDefinition('webit_shipment.serializer.model_handler')) {
            $locator = $container->getDefinition('jms_serializer.metadata.file_locator');
            $dirs = $locator->getArgument(0);
            $dirs['Webit\Shipment'] = '%kernel.root_dir%/../vendor/webit/shipment/src/Resources/serializer';
            $locator->replaceArgument(0, $dirs);
        }
    }
}
