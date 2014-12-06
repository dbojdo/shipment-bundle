<?php
/**
 * File Address.php
 * Created at: 2014-12-06 19-38
 *
 * @author Daniel Bojdo <daniel.bojdo@web-it.eu>
 */

namespace Webit\Bundle\ShipmentBundle\Features\MockEntity;


use Webit\Addressing\Model\CountryAwareAddress;
use Webit\Shipment\Address\DeliveryAddressInterface;
use Webit\Shipment\Address\SenderAddressInterface;

class Address extends CountryAwareAddress implements SenderAddressInterface, DeliveryAddressInterface
{
    /**
     * @return string
     */
    public function getContactPerson()
    {
        // TODO: Implement getContactPerson() method.
    }

    /**
     * @param string $contactPerson
     */
    public function setContactPerson($contactPerson)
    {
        // TODO: Implement setContactPerson() method.
    }

    /**
     * @return string
     */
    public function getContactPhoneNo()
    {
        // TODO: Implement getContactPhoneNo() method.
    }

    /**
     * @param string $contactPhoneNo
     */
    public function setContactPhoneNo($contactPhoneNo)
    {
        // TODO: Implement setContactPhoneNo() method.
    }

    /**
     * @return string
     */
    public function getContactEmail()
    {
        // TODO: Implement getContactEmail() method.
    }

    /**
     * @param string $email
     */
    public function setContactEmail($email)
    {
        // TODO: Implement setContactEmail() method.
    }
}
