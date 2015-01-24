<?php
/**
 * File: ConsignmentEntityRepository.php
 * Created at: 2014-11-30 19:00
 */
 
namespace Webit\Bundle\ShipmentBundle\Repository;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityRepository;
use Webit\Shipment\Consignment\ConsignmentInterface;
use Webit\Shipment\Consignment\ConsignmentRepositoryInterface;
use Webit\Shipment\Parcel\ParcelInterface;
use Webit\Tools\Data\FilterCollection;
use Webit\Tools\Data\SorterCollection;

/**
 * Class ConsignmentEntityRepository
 * @author Daniel Bojdo <daniel.bojdo@web-it.eu>
 */
class ConsignmentEntityRepository extends EntityRepository implements ConsignmentRepositoryInterface
{
    /**
     * @param mixed $id
     * @return ConsignmentInterface
     */
    public function getConsignment($id)
    {
        return $this->find($id);
    }

    /**
     * @param FilterCollection $filters
     9* @param SorterCollection $sorters
     * @param int $limit
     * @param int $offset
     * @return ArrayCollection
     */
    public function getConsignments(
        FilterCollection $filters = null,
        SorterCollection $sorters = null,
        $limit = 50,
        $offset = 0
    ) {

        // TODO: filter / sorter support
        return $this->findBy(array(), array(), $limit, $offset);
    }

    /**
     * @return ConsignmentInterface
     */
    public function createConsignment()
    {
        $cls = $this->getClassName();

        return new $cls();
    }

    /**
     * @param ConsignmentInterface $consignment
     */
    public function saveConsignment(ConsignmentInterface $consignment)
    {
        if ($this->_em->contains($consignment) == false) {
            /** @var ParcelInterface $parcel */
            foreach ($consignment->getParcels() as $parcel) {
                $parcel->setConsignment($consignment);
            }

            $this->_em->persist($consignment);
        }
    }

    /**
     * @param ConsignmentInterface $consignment
     */
    public function removeConsignment(ConsignmentInterface $consignment)
    {
        if ($this->_em->contains($consignment)) {
            $this->_em->remove($consignment);
        }
    }
}
