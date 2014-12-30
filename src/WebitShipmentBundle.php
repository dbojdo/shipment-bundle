<?php
/**
 * File: WebitShipmentBundle.php
 * Created at: 2014-11-30 18:40
 */
 
namespace Webit\Bundle\ShipmentBundle;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Webit\Bundle\ShipmentBundle\DependencyInjection\Compiler\ResolveTargetEntityPass;
use Webit\Bundle\ShipmentBundle\DependencyInjection\Compiler\SerializerPass;
use Webit\Bundle\ShipmentBundle\DependencyInjection\Compiler\VendorAdapterPass;

/**
 * Class WebitShipmentBundle
 * @author Daniel Bojdo <daniel.bojdo@web-it.eu>
 */
class WebitShipmentBundle extends Bundle
{
    public function build(ContainerBuilder $container) {
        parent::build($container);

        $container->addCompilerPass(new ResolveTargetEntityPass('webit_shipment.entity_map'));
        $container->addCompilerPass(new VendorAdapterPass());
        $container->addCompilerPass(new SerializerPass());
    }
}
