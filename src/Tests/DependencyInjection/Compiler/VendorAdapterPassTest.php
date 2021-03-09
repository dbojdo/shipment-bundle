<?php
/**
 * File: VendorAdapterPassTest.php
 * Created at: 2014-12-01 05:59
 */
 
namespace Webit\Bundle\ShipmentBundle\Tests\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Reference;
use Webit\Bundle\ShipmentBundle\DependencyInjection\Compiler\VendorAdapterPass;

/**
 * Class VendorAdapterPassTest
 * @author Daniel Bojdo <daniel.bojdo@web-it.eu>
 */
class VendorAdapterPassTest extends \PHPUnit_Framework_TestCase
{
    private $container;

    private $provider;

    private $factory;

    public function setUp()
    {
        $provider = $this->createDefinition();
        $factory = $this->createDefinition();

        $container = $this->createContainer();
        $container->expects($this->once())
            ->method('findTaggedServiceIds')
            ->with($this->equalTo('webit_shipment.vendor_adapter'))
            ->willReturn(array('service1' => array(), 'service2' => array()));

        $container->expects($this->at(0))
            ->method('getDefinition')
            ->with($this->equalTo('webit_shipment.vendor_adapter_provider'))
            ->willReturn($provider);

        $container->expects($this->at(1))
            ->method('getDefinition')
            ->with($this->equalTo('webit_shipment.repository.vendor.factory'))
            ->willReturn($factory);

        $this->container = $container;
        $this->provider = $provider;
        $this->factory = $factory;
    }

    /**
     * @test
     */
    public function shouldRegisterAdaptersInProvider()
    {
        $this->provider->expects($this->exactly(2))
            ->method('addMethodCall')
            ->with($this->equalTo('registerVendorAdapter'), $this->isType('array'));

        $compiler = new VendorAdapterPass();
        $compiler->process($this->container);
    }

    public function shouldRegisterAdaptersInRepositoryFactory()
    {
        $this->factory->expects($this->exactly(2))
            ->method('addMethodCall')
            ->with($this->equalTo('addVendorFactory'), $this->isType('array'));

        $compiler = new VendorAdapterPass();
        $compiler->process($this->container);
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
    private function createDefinition()
    {
        $def = $this->getMock('Symfony\Component\DependencyInjection\Definition');

        return $def;
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|Reference
     */
    private function createReference()
    {
        $def = $this->getMock('Symfony\Component\DependencyInjection\Reference');

        return $def;
    }
}
 