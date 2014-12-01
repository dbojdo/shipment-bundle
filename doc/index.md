# Installation and configuration of  **Webit Shipment Bundle**

## Composer

Add the **webit/shipment-bundle** into **composer.json**

```json
{
    "require": {
        "php":              ">=5.3.2",
        "webit/shipment-bundle": "dev-master"
    },
    "autoload": {
        "psr-0": {
            "Acme": "src/"
        }
    }
}
```

## Register bundle in Kernel

Add following line:

```
// in AppKernel::registerBundles()
$bundles = array(
    // ...
    new Webit\Bundle\ShipmentBundle(),
    // ...
);
```

# Configuration

## Bundle configuration

```
webit_shipment:
    orm:
        entities:
            consignment: Webit\Bundle\ShipmentBundle\Entity\Consignment # default, can skip
            parcel: Webit\Bundle\ShipmentBundle\Entity\Parcel # default, can skip
            dispatch_confirmation: Webit\Bundle\ShipmentBundle\Entity\DispatchConfirmation # default, can skip
            sender_address: Your\SenderAddress\Entity # required
            delivery_address: Your\DeliveryAddress\Entity # required
```

## Mapping

If you're going to use default implementation for entities add following lines to ORM configuration:

```
yaml
doctrine:
    dbal:
    # your dbal config here
    orm:
        auto_generate_proxy_classes: %kernel.debug%
        auto_mapping: true
        # only these lines are added additionally
        mappings:
            shipment:
                type: xml
                prefix: Webit\Bundle\ShipmentBundle\Entity
                dir: @WebitShipmentBundle/Resources/doctrine/orm
```
