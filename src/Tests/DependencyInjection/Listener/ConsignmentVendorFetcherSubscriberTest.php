<?php
/**
 * File: ConsignmentVendorFetcherSubscriberTest.php
 * Created at: 2014-12-01 06:17
 */

namespace Webit\Bundle\ShipmentBundle\Tests\Listener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Webit\Bundle\ShipmentBundle\Entity\Consignment;
use Webit\Bundle\ShipmentBundle\Listener\ConsignmentVendorFetcherSubscriber;
use Webit\Shipment\Vendor\VendorInterface;
use Webit\Shipment\Vendor\VendorRepositoryInterface;

/**
 * Class ConsignmentVendorFetcherSubscriberTest
 * @author Daniel Bojdo <daniel.bojdo@web-it.eu>
 */
class ConsignmentVendorFetcherSubscriberTest extends \PHPUnit_Framework_TestCase
{



    /**
     * @test
     */
    public function shouldRegisterOnPostLoad()
    {
        $vendorRepository = $this->createVendorRepository();
        $listener = new ConsignmentVendorFetcherSubscriber($vendorRepository);
        $this->assertContains('postLoad', $listener->getSubscribedEvents());
    }

    /**
     * @test
     */
    public function shouldFetchVendorFromRepository()
    {
        $vendor = $this->createVendor();

        $consignment = $this->createConsignment();
        $consignment->expects($this->any())->method('getVendor')->willReturn('vendor-1');
        $consignment->expects($this->once())->method('setVendor')->with($this->equalTo($vendor));

        $vendorRepository = $this->createVendorRepository();
        $vendorRepository->expects($this->once())->method('getVendor')->with('vendor-1')->willReturn($vendor);

        $event = $this->createEvent();
        $event->expects($this->once())->method('getEntity')->willReturn($consignment);

        $container = $this->createContainer();
        $container->expects($this->any())->method('get')->willReturn($vendorRepository);

        $listener = new ConsignmentVendorFetcherSubscriber();
        $listener->setContainer($container);

        $listener->postLoad($event);
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|VendorRepositoryInterface
     */
    private function createVendorRepository()
    {
        $repo = $this->getMock('Webit\Shipment\Vendor\VendorRepositoryInterface');

        return $repo;
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|LifecycleEventArgs
     */
    private function createEvent()
    {
        $event = $this->getMockBuilder('Doctrine\ORM\Event\LifecycleEventArgs')
            ->disableOriginalConstructor()
            ->getMock();

        return $event;
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|VendorInterface
     */
    private function createVendor()
    {
        $vendor = $this->getMock('Webit\Shipment\Vendor\VendorInterface');

        return $vendor;
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|Consignment
     */
    private function createConsignment()
    {
        $consignment = $this->getMock('Webit\Bundle\ShipmentBundle\Entity\Consignment');

        return $consignment;
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|ContainerInterface
     */
    private function createContainer()
    {
        $container = $this->getMock('Symfony\Component\DependencyInjection\ContainerInterface');

        return $container;
    }
}
