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
    private $mapParameter;

    public function __construct($mapParameter)
    {
        $this->mapParameter = $mapParameter;
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

        $resolveTargetEntityListener = $container->getDefinition('doctrine.orm.listeners.resolve_target_entity');

        $entityMap = $container->getParameter($this->mapParameter);

        foreach ($entityMap as $key => $mapEntry) {
            $resolveTargetEntityListener
                ->addMethodCall('addResolveTargetEntity', array(
                    $mapEntry['interface'],
                    $mapEntry['target_entity'],
                    array()
                ))
            ;

            $repoService = sprintf('webit_shipment.repository.%s', $key);
            if ($container->has($repoService)) {
                $repo = $container->findDefinition($repoService);
                $repo->replaceArgument(0, $mapEntry['target_entity']);
            }
        }

        if (!$resolveTargetEntityListener->hasTag('doctrine.event_listener')) {
            $resolveTargetEntityListener->addTag('doctrine.event_listener', array('event' => 'loadClassMetadata'));
        }
    }
}
