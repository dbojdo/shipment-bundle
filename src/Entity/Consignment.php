<?php
/**
 * File: Consignment.php
 * Created at: 2014-11-30 18:52
 */
 
namespace Webit\Bundle\ShipmentBundle\Entity;

use Webit\Shipment\Consignment\Consignment as BaseConsignment;
use Webit\Shipment\Vendor\VendorInterface;

/**
 * Class Consignment
 * @author Daniel Bojdo <daniel.bojdo@web-it.eu>
 */
class Consignment extends BaseConsignment
{
    /** @var int */
    protected $id;

    public function getId()
    {
        return $this->id;
    }
}
 