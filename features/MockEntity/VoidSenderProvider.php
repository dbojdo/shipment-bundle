<?php
/**
 * File VoidSenderProvider.php
 * Created at: 2017-09-16 12:08
 *
 * @author Daniel Bojdo <daniel.bojdo@web-it.eu>
 */

namespace Webit\Bundle\ShipmentBundle\Features\MockEntity;

use Webit\Shipment\Address\DefaultSenderAddressProviderInterface;
use Webit\Shipment\Feature\Model\SenderAddress;

class VoidSenderProvider implements DefaultSenderAddressProviderInterface
{

    /**
     * @inheritdoc
     */
    public function getSender()
    {
        return new SenderAddress();
    }
}