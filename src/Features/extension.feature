Feature: WebitShipmentBundle - Service container extension
  In order to bootstrap Shipment related services
  As a developer
  I want register Shipment libraries services in Container

  Background:
    Given application config contains:
    """
    webit_shipment:
        model_map:
            sender_address: Webit\Bundle\ShipmentBundle\Features\MockEntity\Address
            delivery_address: Webit\Bundle\ShipmentBundle\Features\MockEntity\Address
        orm: true
    """

  Scenario: Loading services
    When application is up
    Then there should be following services in container:
    """
    webit_shipment.manager.default, webit_shipment.vendor_adapter_provider,
    webit_shipment.repository.vendor.in_memory, webit_shipment.repository.vendor.in_memory_factory,
    webit_shipment.repository.consignment, webit_shipment.repository.parcel,
    webit_shipment.repository.dispatch_confirmation, webit_shipment.subscriber.consignment_vendor_fetcher
    """

  Scenario: Entity mapping
    When application is up
    Then there should be valid mapping

  Scenario: JMS Serializer support
    Given application config contains:
    """
    webit_shipment:
        model_map:
            sender_address: Webit\Bundle\ShipmentBundle\Features\MockEntity\Address
            delivery_address: Webit\Bundle\ShipmentBundle\Features\MockEntity\Address
        orm: true
        jms_serializer: true
    """
    Then there should be following services in container:
    """
    webit_shipment.serializer.model_handler, webit_shipment.serializer.collection_handler
    """
