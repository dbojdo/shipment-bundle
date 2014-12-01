<?php
/**
 * File: ConsignmentVendorFetcherSubscriber.php
 * Created at: 2014-11-30 19:31
 */
 
namespace Webit\Bundle\ShipmentBundle\Listener;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Webit\Bundle\ShipmentBundle\Entity\Consignment;
use Webit\Shipment\Vendor\VendorInterface;
use Webit\Shipment\Vendor\VendorRepositoryInterface;

/**
 * Class ConsignmentVendorFetcherSubscriber
 * @author Daniel Bojdo <daniel.bojdo@web-it.eu>
 */
class ConsignmentVendorFetcherSubscriber implements EventSubscriber
{
    /**
     * @var VendorRepositoryInterface
     */
    private $vendorRepository;

    public function __construct(VendorRepositoryInterface $vendorRepository)
    {
        $this->vendorRepository = $vendorRepository;
    }

    /**
     * @return array
     */
    public function getSubscribedEvents()
    {
        return array(
            'postLoad'
        );
    }

    /**
     * @param LifecycleEventArgs $event
     */
    public function postLoad(LifecycleEventArgs $event)
    {
        $entity = $event->getEntity();
        if ($entity instanceof Consignment) {
            $vendorCode = $this->getVendorCode($entity);
            if ($vendorCode && $vendor = $this->vendorRepository->getVendor($vendorCode)) {
                $entity->setVendor($vendor);
            }
        }
    }

    /**
     * @param Consignment $consignment
     * @return int
     */
    private function getVendorCode(Consignment $consignment)
    {
        $vendorCode = $consignment->getVendor();
        return $vendorCode && is_scalar($vendorCode) ? $vendorCode : null;
    }
}
