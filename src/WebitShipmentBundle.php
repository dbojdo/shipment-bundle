<?php
/**
 * File: WebitShipmentBundle.php
 * Created at: 2014-11-30 18:40
 */
 
namespace Webit\Bundle\ShipmentBundle;

use Doctrine\DBAL\DBALException;
use Doctrine\DBAL\Types\Type;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Webit\Bundle\ShipmentBundle\DependencyInjection\Compiler\ResolveTargetEntityPass;
use Webit\Bundle\ShipmentBundle\DependencyInjection\Compiler\SerializerPass;
use Webit\Bundle\ShipmentBundle\DependencyInjection\Compiler\VendorAdapterPass;
use Webit\Shipment\DBAL\VendorOptionCollectionType;
use Webit\Shipment\DBAL\VendorOptionValueCollectionType;

/**
 * Class WebitShipmentBundle
 * @author Daniel Bojdo <daniel.bojdo@web-it.eu>
 */
class WebitShipmentBundle extends Bundle
{
    /**
     * @var array
     */
    private static $dbalTypes = array(
        VendorOptionCollectionType::TYPE_NAME => 'Webit\Shipment\DBAL\VendorOptionCollectionType',
        VendorOptionValueCollectionType::TYPE_NAME => 'Webit\Shipment\DBAL\VendorOptionValueCollectionType'
    );

    public function build(ContainerBuilder $container) {
        $this->registerDoctrineDBALTypes();

        parent::build($container);

        $container->addCompilerPass(new ResolveTargetEntityPass('webit_shipment.entity_map'));
        $container->addCompilerPass(new VendorAdapterPass());
        $container->addCompilerPass(new SerializerPass());
    }

    public function boot()
    {
        $this->registerDoctrineDBALTypes();
    }

    private function registerDoctrineDBALTypes()
    {
        foreach (self::$dbalTypes as $typeName => $class) {
            try {
                Type::addType($typeName, $class);
            } catch (DBALException $e) {
            }
        }
    }
}
