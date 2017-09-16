<?php
/**
 * File: Configuration.php
 * Created at: 2014-11-30 18:57
 */
 
namespace Webit\Bundle\ShipmentBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Class Configuration
 * @author Daniel Bojdo <daniel.bojdo@web-it.eu>
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('webit_shipment');

        $rootNode
        ->children()
            ->scalarNode('jms_serializer')->defaultFalse()->end()
            ->scalarNode('orm')->defaultFalse()->end()
            ->arrayNode('vendor')->addDefaultsIfNotSet()
                ->children()
                    ->scalarNode('cache_service')->cannotBeEmpty()->isRequired()->end()
                    ->scalarNode('cache_enabled')->defaultTrue()->end()
                ->end()
            ->end()
            ->scalarNode('default_sender_address_provider')->cannotBeEmpty()->isRequired()->end()
            ->arrayNode('model_map')->addDefaultsIfNotSet()
                ->children()
                    ->scalarNode('vendor')->defaultValue('Webit\Shipment\Vendor\Vendor')->cannotBeEmpty()->end()
                    ->scalarNode('consignment')->defaultValue('Webit\Bundle\ShipmentBundle\Entity\Consignment')->cannotBeEmpty()->end()
                    ->scalarNode('parcel')->defaultValue('Webit\Bundle\ShipmentBundle\Entity\Parcel')->cannotBeEmpty()->end()
                    ->scalarNode('dispatch_confirmation')->defaultValue('Webit\Bundle\ShipmentBundle\Entity\DispatchConfirmation')->cannotBeEmpty()->end()
                    ->scalarNode('sender_address')->isRequired()->cannotBeEmpty()->end()
                    ->scalarNode('delivery_address')->isRequired()->cannotBeEmpty()->end()
                ->end()
            ->end()
        ->end();

        return $treeBuilder;
    }
}
