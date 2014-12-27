<?php
/**
 * File: ResolveTargetEntityPassTest.php
 * Created at: 2014-12-01 05:20
 */
 
namespace Webit\Bundle\ShipmentBundle\Tests\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Webit\Bundle\ShipmentBundle\DependencyInjection\Compiler\ResolveTargetEntityPass;

/**
 * Class ResolveTargetEntityPassTest
 * @author Daniel Bojdo <daniel.bojdo@web-it.eu>
 */
class ResolveTargetEntityPassTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function shouldAddResolvedEntities()
    {
        $map = array(
            'i1' => array('interface' => 'interface1', 'target_entity' => 'class1'),
            'i2' => array('interface' => 'interface2', 'target_entity' => 'class2'),
        );

        $resolverDefinition = $this->createResolverDefinition();
        $resolverDefinition
            ->expects($this->exactly(2))
            ->method('addMethodCall')
            ->withConsecutive(
                array(
                    'addResolveTargetEntity',
                    $this->equalTo(
                        array('interface1', 'class1', array())
                    )
                ),
                array(
                    'addResolveTargetEntity',
                    $this->equalTo(
                        array('interface2', 'class2', array())
                    )
                )
            );

        $container = $this->createContainer();
        $container->expects($this->once())->method('getParameter')->with($this->equalTo('map_config_key'))->willReturn($map);

        $container->expects($this->once())
                    ->method('hasDefinition')
                    ->with($this->equalTo('doctrine.orm.listeners.resolve_target_entity'))
                    ->willReturn(true);

        $container->expects($this->once())
                    ->method('getDefinition')
                    ->with($this->equalTo('doctrine.orm.listeners.resolve_target_entity'))
                    ->willReturn($resolverDefinition);

        $compiler = new ResolveTargetEntityPass('map_config_key');
        $compiler->process($container);
    }

    /**
     * @test
     * @expectedException \RuntimeException
     */
    public function shouldThrowExceptionWhenRTELNotFound()
    {
        $container = $this->createContainer();
        $container->expects($this->once())
            ->method('hasDefinition')
            ->with($this->equalTo('doctrine.orm.listeners.resolve_target_entity'))
            ->willReturn(false);

        $compiler = new ResolveTargetEntityPass(array());
        $compiler->process($container);
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|ContainerBuilder
     */
    private function createContainer () {
        $container = $this->getMockBuilder('Symfony\Component\DependencyInjection\ContainerBuilder')
                            ->disableOriginalConstructor()
                            ->getMock();
        return $container;
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|Definition
     */
    private function createResolverDefinition()
    {
        $def = $this->getMock('Symfony\Component\DependencyInjection\Definition');

        return $def;
    }
}
