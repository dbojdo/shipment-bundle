<?php
/**
 * File: DispatchConfirmation.php
 * Created at: 2014-11-30 18:59
 */
 
namespace Webit\Bundle\ShipmentBundle\Entity;

use Webit\Shipment\Consignment\DispatchConfirmation as BaseDispatchConfirmation;

/**
 * Class DispatchConfirmation
 * @author Daniel Bojdo <daniel.bojdo@web-it.eu>
 */
class DispatchConfirmation extends BaseDispatchConfirmation
{
    /**
     * @var int
     */
    protected $id;

    public function getId()
    {
        return $this->id;
    }
}
