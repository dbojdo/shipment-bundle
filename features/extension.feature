Feature: WebitShipmentBundle - Service container extension
  In order to bootstrap Shipment related services
  As a developer
  I want register Shipment libraries services in Container

  Background:
    Given the configuration contains:
    """
    framework:
        secret: secret-token

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
                    dir: %kernel.root_dir%/../../src/Resources/config/doctrine/orm
                WebitShipmentTest:
                    type: annotation
                    prefix: Webit\Bundle\ShipmentBundle\Features\MockEntity
                    dir: %kernel.root_dir%/../MockEntity
                    is_bundle: false

    webit_shipment:
        model_map:
            sender_address: Webit\Bundle\ShipmentBundle\Features\MockEntity\Address
            delivery_address: Webit\Bundle\ShipmentBundle\Features\MockEntity\Address
        orm: true
    """

  Scenario: Loading services
    When I bootstrap the application
    Then there should be following services defined:
    """
    webit_shipment.vendor_adapter_provider,
    webit_shipment.repository.vendor.in_memory, webit_shipment.repository.vendor.in_memory_factory,
    webit_shipment.repository.consignment, webit_shipment.repository.parcel,
    webit_shipment.repository.dispatch_confirmation, webit_shipment.subscriber.consignment_vendor_fetcher
    """
    And there should be following aliases defined:
    | service                                    | alias                  |
    | webit_shipment.consignment_manager.default | webit_shipment.manager |

  Scenario: Entity mapping
    When I bootstrap the application
    Then there should be valid ORM mapping

  Scenario: JMS Serializer support
    Given the configuration contains:
    """
    webit_shipment:
        model_map:
            sender_address: Webit\Bundle\ShipmentBundle\Features\MockEntity\Address
            delivery_address: Webit\Bundle\ShipmentBundle\Features\MockEntity\Address
        orm: true
        jms_serializer: true
    """
    When I bootstrap the application
    Then there should be following services defined:
    """
    webit_shipment.serializer.model_handler, webit_shipment.serializer.collection_handler
    """