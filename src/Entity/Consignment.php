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
    /**
     * @var string
     */
    protected $vendorCode;

    /**
     * @param VendorInterface $vendor
     */
    public function setVendor(VendorInterface $vendor)
    {
        parent::setVendor($vendor);
        $this->updateVendorCode();
    }

    public function onPostLoad()
    {
        if (! $this->vendor) {
            $this->vendor = $this->vendorCode;
        }
    }

    public function updateVendorCode()
    {
        $this->vendorCode = $this->vendor ? $this->vendor->getCode() : null;
    }
}
 