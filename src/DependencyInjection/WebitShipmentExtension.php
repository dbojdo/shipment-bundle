<?php
/**
 * File: WebitShipmentExtension.php
 * Created at: 2014-11-30 18:55
 */
 
namespace Webit\Bundle\ShipmentBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;

/**
 * Class WebitShipmentExtension
 * @author Daniel Bojdo <daniel.bojdo@web-it.eu>
 */
class WebitShipmentExtension extends Extension
{
    /**
     * @param array $configs
     * @param ContainerBuilder $container
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $this->setEntityMap($container, $config['orm']['entities']);

        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('shipment.xml');
        $loader->load('vendor.xml');
        $loader->load('orm.xml');
    }

    private function setEntityMap(ContainerBuilder $container, array $entityConfig)
    {
        $interfaceMap = array(
            'consignment' => 'Webit\Shipment\Consignment\ConsignmentInterface',
            'parcel' => 'Webit\Shipment\Parcel\ParcelInterface',
            'dispatch_confirmation' =>'Webit\Shipment\Consignment\DispatchConfirmationInterface',
            'sender_address' => 'Webit\Shipment\Address\SenderAddressInterface',
            'delivery_address' => 'Webit\Shipment\Address\DeliveryAddressInterface',
        );

        $entityMap = array();
        foreach ($interfaceMap as $key => $interface) {
            $entityMap[$interface] = $entityConfig[$key];
        }

        $container->setParameter('webit_shipment.entity_map', $entityMap);
    }
}
 