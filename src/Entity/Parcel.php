<?php
/**
 * File: Parcel.php
 * Created at: 2014-11-30 18:53
 */
 
namespace Webit\Bundle\ShipmentBundle\Entity;

use Webit\Shipment\Parcel\Parcel as BaseParcel;

/**
 * Class Parcel
 * @author Daniel Bojdo <daniel.bojdo@web-it.eu>
 */
class Parcel extends BaseParcel
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
