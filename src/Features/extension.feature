Feature: WebitShipmentBundle - Service container extension
  In order to bootstrap Shipment related services
  As a developer
  I want register Shipment libraries services in Container

  Scenario: Loading services
    Given application config contains:
    """
    doctrine:
        dbal:
            driver:   pdo_sqlite
            charset:  UTF8
        orm:
            auto_generate_proxy_classes: %kernel.debug%
            auto_mapping: true
            mappings:
                WebitShipmentBundle:
                    type: xml
                    prefix: Webit\Bundle\ShipmentBundle\Entity
                    dir: %kernel.root_dir%/../../Resources/config/doctrine/orm

    webit_shipment:
        orm:
            entities:
                sender_address: Webit\Bundle\ShipmentBundle\Features\MockEntity\Address
                delivery_address: Webit\Bundle\ShipmentBundle\Features\MockEntity\Address
    """
    And application is up
    Then there should be following services in container:
    """
    webit_shipment.manager.default, webit_shipment.vendor_adapter_provider,
    webit_shipment.repository.vendor.in_memory, webit_shipment.repository.vendor.in_memory_factory,
    webit_shipment.repository.consignment, webit_shipment.repository.parcel,
    webit_shipment.repository.dispatch_confirmation, webit_shipment.subscriber.consignment_vendor_fetcher
    """
