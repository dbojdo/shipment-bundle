# WebitShipmentBundle

Shipment library Symfony 2 integration

## Installation
### via Composer

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

### Register bundle in Kernel
Add following lines:

```
// in AppKernel::registerBundles()
$bundles = array(
    // ...
    new Webit\Bundle\ShipmentBundle\WebitShipmentBundle(),
    new Doctrine\Bundle\DoctrineBundle\DoctrineBundle()
    // ...
);
```

***Notice:*** Remember to add WebitShipmentBundle ***before*** DoctrineBundle (because of Target Entity Resolver)

## Configuration
You can define as much accounts as you need (ADE Accounts and Track & Trace as well)

```
webit_shipment:
    orm:
        entities:
            sender_address: Your\SenderAddress\Entity # required
            delivery_address: Your\DeliveryAddress\Entity # required
            consignment: Webit\Bundle\ShipmentBundle\Entity\Consignment # default, can skip
            parcel: Webit\Bundle\ShipmentBundle\Entity\Parcel # default, can skip
            dispatch_confirmation: Webit\Bundle\ShipmentBundle\Entity\DispatchConfirmation # default, can skip
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
            WebitShipmentBundle:
                type: xml
                prefix: Webit\Bundle\ShipmentBundle\Entity
                dir: %kernel.root_dir%/../vendor/webit/shipment-bundle/src/Resources/config/doctrine/orm
```

To learn more about ***shipment library*** see [https://github.com/dbojdo/shipment](https://github.com/dbojdo/shipment "Shipment Library")
