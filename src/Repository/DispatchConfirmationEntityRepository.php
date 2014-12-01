<?php
/**
 * File: DispatchConfirmationEntityRepository.php
 * Created at: 2014-12-01 04:15
 */
 
namespace Webit\Bundle\ShipmentBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\UnitOfWork;
use Webit\Shipment\Consignment\DispatchConfirmationInterface;
use Webit\Shipment\Consignment\DispatchConfirmationRepositoryInterface;
use Webit\Shipment\Vendor\VendorInterface;

/**
 * Class DispatchConfirmationEntityRepository
 * @author Daniel Bojdo <daniel.bojdo@web-it.eu>
 */
class DispatchConfirmationEntityRepository extends EntityRepository implements DispatchConfirmationRepositoryInterface
{
    /**
     * @param VendorInterface $vendor
     * @param string $number
     * @return \Webit\Shipment\Consignment\DispatchConfirmationInterface
     */
    public function getDispatchConfirmation(VendorInterface $vendor, $number)
    {
        $qb = $this->createQueryBuilder('dc');

        $qb->select('dc');
        $qb->innerJoin('dc.consignments', 'c', 'WITH', $qb->expr()->eq('c.vendor', ':vendor'));
        $qb->where($qb->expr()->eq('dc.number', ':number'));
        $qb->setMaxResults(1);

        $query = $qb->getQuery();
        $query->setParameters(array(
            'vendor' => $vendor,
            'number' => $number
        ));

        return $qb->getQuery()->getOneOrNullResult();
    }

    /**
     * @param DispatchConfirmationInterface $dispatchConfirmation
     */
    public function updateDispatchConfirmation(DispatchConfirmationInterface $dispatchConfirmation)
    {
        if (! $this->_em->contains($dispatchConfirmation)) {
            $this->_em->persist($dispatchConfirmation);
        }

        if ($this->_em->getUnitOfWork()->getEntityState($dispatchConfirmation) == UnitOfWork::STATE_DETACHED) {
            $this->_em->merge($dispatchConfirmation);
        }
    }

    /**
     * @param DispatchConfirmationInterface $dispatchConfirmation
     */
    public function removeDispatchConfirmation(DispatchConfirmationInterface $dispatchConfirmation)
    {
        if (! $this->_em->contains($dispatchConfirmation)) {
            $this->_em->remove($dispatchConfirmation);
        }
    }

}
 