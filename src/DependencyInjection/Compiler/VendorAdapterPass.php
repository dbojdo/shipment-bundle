<?php
/**
 * File: VendorAdapterPass.php
 * Created at: 2014-11-30 20:16
 */
 
namespace Webit\Bundle\ShipmentBundle\DependencyInjection\Compiler;

use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Class VendorAdapterPass
 * @author Daniel Bojdo <daniel.bojdo@web-it.eu>
 */
class VendorAdapterPass implements CompilerPassInterface
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
        $provider = $container->getDefinition('webit_shipment.vendor_adapter_provider');
        $vendorRepositoryFactory = $container->getDefinition('webit_shipment.repository.vendor.factory');

        $adapters = $container->findTaggedServiceIds('webit_shipment.vendor_adapter');
        foreach ($adapters as $adapter => $tags) {
            foreach ($tags as $tag) {
                $vendor = isset($tag['vendor']) ? $tag['vendor'] : null;
                if (empty($vendor)) {
                    throw new \UnexpectedValueException('Missing mandatory tag key "vendor"');
                }

                $adapterDefinition = $container->findDefinition($adapter);
                $adapterDefinition->setLazy(true);

                $provider->addMethodCall('registerVendorAdapter', array(new Reference($adapter), $vendor));
                $vendorRepositoryFactory->addMethodCall('addVendorFactory', array(new Reference($adapter), $vendor));
            }
        }
    }
}
