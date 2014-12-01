<?php
/**
 * File: ResolveTargetEntityPass.php
 * Created at: 2014-11-30 19:53
 */
 
namespace Webit\Bundle\ShipmentBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Class ResolveTargetEntityPass
 * @author Daniel Bojdo <daniel.bojdo@web-it.eu>
 */
class ResolveTargetEntityPass implements CompilerPassInterface
{
    /**
     * @var array
     */
    private $entityMap;

    public function __construct(array $entityMap)
    {
        $this->entityMap = $entityMap;
    }

    /**
     * You can modify the container here before it is dumped to PHP code.
     *
     * @param ContainerBuilder $container
     *
     * @api
     */
    public function process(ContainerBuilder $container)
    {

        if (!$container->hasDefinition('doctrine.orm.listeners.resolve_target_entity')) {
            throw new \RuntimeException('Cannot find Doctrine RTEL');
        }

        $resolveTargetEntityListener = $container->findDefinition('doctrine.orm.listeners.resolve_target_entity');
        foreach ($this->entityMap as $interface => $targetEntity) {
            $resolveTargetEntityListener
                ->addMethodCall('addResolveTargetEntity', array(
                    $interface,
                    $targetEntity,
                    array()
                ))
            ;
        }
    }
}
 