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
     * @inheritdoc
     */
    public function getDispatchConfirmation(VendorInterface $vendor, $number)
    {
        $qb = $this->createQueryBuilder('dc');

        $qb->select('dc');
        $qb->innerJoin(
            'dc.consignments',
            'c',
            'WITH',
            $qb->expr()->eq('c.vendor', ':vendor')
        );
        $qb->where($qb->expr()->eq('dc.number', ':number'));
        $qb->setMaxResults(1);

        $qb->setParameters(array(
            'vendor' => $vendor->getCode(),
            'number' => $number
        ));

        $query = $qb->getQuery();

        return $query->getOneOrNullResult();
    }

    /**
     * @inheritdoc
     */
    public function updateDispatchConfirmation(DispatchConfirmationInterface $dispatchConfirmation)
    {
        if (! $this->_em->contains($dispatchConfirmation)) {
            $this->_em->persist($dispatchConfirmation);
        }

        if ($this->_em->getUnitOfWork()->getEntityState($dispatchConfirmation) == UnitOfWork::STATE_DETACHED) {
            $this->_em->merge($dispatchConfirmation);
        }
        $this->_em->flush();
    }

    /**
     * @inheritdoc
     */
    public function removeDispatchConfirmation(DispatchConfirmationInterface $dispatchConfirmation)
    {
        if (! $this->_em->contains($dispatchConfirmation)) {
            $this->_em->remove($dispatchConfirmation);
        }
        $this->_em->flush();
    }

    /**
     * @inheritdoc
     */
    public function createDispatchConfirmation()
    {
        $class = $this->getClassName();
        return new $class;
    }

    /**
     * @inheritdoc
     */
    public function saveDispatchConfirmation(DispatchConfirmationInterface $dispatchConfirmation)
    {
        if (! $this->_em->contains($dispatchConfirmation)) {
            $this->_em->persist($dispatchConfirmation);
        }
        $this->_em->flush();
    }
}
