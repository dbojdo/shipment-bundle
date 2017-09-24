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
use Webit\Shipment\Consignment\ConsignmentStatusList;
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
     * @inheritdoc
     */
    public function getConsignment($id)
    {
        return $this->find($id);
    }

    /**
     * @inheritdoc
     */
    public function getConsignments(
        FilterCollection $filters = null,
        SorterCollection $sorters = null,
        $limit = 50,
        $offset = 0
    ) {

        // TODO: filter / sorter support
        $results = $this->findBy(array(), array(), $limit, $offset);

        return new ArrayCollection($results);
    }

    /**
     * @inheritdoc
     */
    public function createConsignment()
    {
        $cls = $this->getClassName();

        return new $cls();
    }

    /**
     * @inheritdoc
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
        $this->_em->flush();
    }

    /**
     * @inheritdoc
     */
    public function getOpenedConsignments(\DateTime $dateFrom, \DateTime $dateTo)
    {
        $qb = $this->createQueryBuilder('c');
        $qb->where($qb->expr()->in('c.status', ConsignmentStatusList::openedStatuses()));
        $qb->innerJoin(
            'c.dispatchConfirmation',
            'dc',
            'WITH',
            $qb->expr()->andX(
                $qb->expr()->gt('dc.dispatchedAt', ':dateFrom'),
                $qb->expr()->lte('dc.dispatchedAt', ':dateTo')
            )
        );

        $qb->setParameter('dateFrom', $dateFrom);
        $qb->setParameter('dateTo', $dateTo);

        $consignments = $qb->getQuery()->execute();

        return new ArrayCollection($consignments);
    }

    /**
     * @inheritdoc
     */
    public function removeConsignment(ConsignmentInterface $consignment)
    {
        if ($this->_em->contains($consignment)) {
            $this->_em->remove($consignment);
        }
        $this->_em->flush();
    }
}
