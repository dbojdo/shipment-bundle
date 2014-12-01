<?php
/**
 * File: ParcelEntityRepository.php
 * Created at: 2014-11-30 20:11
 */
 
namespace Webit\Bundle\ShipmentBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Webit\Shipment\Parcel\ParcelInterface;
use Webit\Shipment\Parcel\ParcelRepositoryInterface;
use Webit\Shipment\Vendor\VendorInterface;

/**
 * Class ParcelEntityRepository
 * @author Daniel Bojdo <daniel.bojdo@web-it.eu>
 */
class ParcelEntityRepository extends EntityRepository implements ParcelRepositoryInterface
{
    /**
     * @param VendorInterface $vendor
     * @param string $number
     * @return ParcelInterface
     */
    public function getParcel(VendorInterface $vendor, $number)
    {
        $qb = $this->createQueryBuilder('p');

        $qb->select('p', 'c', 'dc');
            $qb->leftJoin('p.consignment', 'c');
            $qb->leftJoin('c.dispatchConfirmation', 'dc');
        $qb->where($qb->expr()->eq('p.number', ':number'))
            ->andWhere($qb->expr()->eq('p.vendorCode',':vendorCode'));
        $qb->setMaxResults(1);

        $query = $qb->getQuery();
        $query->setParameters(array(
            'number' => $number,
            'vendorCode' => $vendor->getCode()
        ));

        return $query->getOneOrNullResult();
    }
}
