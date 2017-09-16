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
    private static $interfaceMap = array(
        'consignment' => 'Webit\Shipment\Consignment\ConsignmentInterface',
        'parcel' => 'Webit\Shipment\Parcel\ParcelInterface',
        'dispatch_confirmation' =>'Webit\Shipment\Consignment\DispatchConfirmationInterface',
        'sender_address' => 'Webit\Shipment\Address\SenderAddressInterface',
        'delivery_address' => 'Webit\Shipment\Address\DeliveryAddressInterface',
        'vendor' => 'Webit\Shipment\Vendor\VendorInterface'
    );

    /**
     * @param array $configs
     * @param ContainerBuilder $container
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $this->setEntityMap($container, $config['model_map']);

        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('shipment.xml');
        $loader->load('vendor.xml');

        $container->setAlias('webit_shipment.vendor_cache', $config['vendor']['cache_service']);

        if ($config['vendor']['cache_enabled']) {
            $container->setAlias('webit_shipment.repository.vendor', 'webit_shipment.repository.vendor.cached');
        }

        $container->setAlias('webit_shipment.default_sender_address_provider', $config['default_sender_address_provider']);

        $loader->load('orm.xml');

        if ($config['jms_serializer'] == true) {
            $loader->load('jms_serializer.xml');
            $this->configJmsSerializer($config, $container);
        }

        $loader->load('print_controller.xml');
    }

    private function setEntityMap(ContainerBuilder $container, array $entityConfig)
    {
        $entityMap = array();

        $interfaces = self::$interfaceMap;
        unset($interfaces['vendor']);

        foreach (self::$interfaceMap as $key => $interface) {
            $entityMap[$key] = array('interface' => $interface, 'target_entity' => $entityConfig[$key]);
        }

        $container->setParameter('webit_shipment.entity_map', $entityMap);
    }

    private function configJmsSerializer(array $config, ContainerBuilder $container)
    {
        $classMap = array();
        foreach (self::$interfaceMap as $key => $interface) {
            $classMap[$interface] = $config['model_map'][$key];
        }

        $modelHandler = $container->getDefinition('webit_shipment.serializer.model_handler');
        $modelHandler->addArgument($classMap);
    }
}
