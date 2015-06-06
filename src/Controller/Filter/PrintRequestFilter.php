<?php
/**
 * File PrintRequestFilter.php
 * Created at: 2015-03-16 06-25
 *
 * @author Daniel Bojdo <daniel.bojdo@web-it.eu>
 */

namespace Webit\Bundle\ShipmentBundle\Controller\Filter;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Translation\Exception\NotFoundResourceException;
use Webit\Bundle\ShipmentBundle\Controller\PrintController;
use Webit\Shipment\Consignment\ConsignmentRepositoryInterface;
use Webit\Shipment\Consignment\DispatchConfirmationRepositoryInterface;
use Webit\Shipment\Vendor\VendorRepositoryInterface;

class PrintRequestFilter implements EventSubscriberInterface
{

    /**
     * @var VendorRepositoryInterface
     */
    private $vendorRepository;

    /**
     * @var DispatchConfirmationRepositoryInterface
     */
    private $dispatchConfirmationRepository;

    /**
     * @var ConsignmentRepositoryInterface
     */
    private $consignmentRepository;

    /**
     * Returns an array of event names this subscriber wants to listen to.
     *
     * The array keys are event names and the value can be:
     *
     *  * The method name to call (priority defaults to 0)
     *  * An array composed of the method name to call and the priority
     *  * An array of arrays composed of the method names to call and respective
     *    priorities, or 0 if unset
     *
     * For instance:
     *
     *  * array('eventName' => 'methodName')
     *  * array('eventName' => array('methodName', $priority))
     *  * array('eventName' => array(array('methodName1', $priority), array('methodName2'))
     *
     * @return array The event names to listen to
     *
     * @api
     */
    public static function getSubscribedEvents()
    {
        return array(
            KernelEvents::CONTROLLER => 'onKernelController'
        );
    }

    public function onKernelController(FilterControllerEvent $event)
    {
        $controller = $event->getController();

        if (! is_array($controller) || ! ($controller[0] instanceof PrintController)) {
            return false;
        }

        $request = $event->getRequest();
        switch($controller[1]) {
            case 'getDispatchConfirmationLabels':
            case 'getDispatchConfirmationReceipt':
                $dispatchConfirmation = $this->getDispatchConfirmation($request);
                if (! $dispatchConfirmation) {
                    throw new NotFoundResourceException('Required Dispatch Confirmation could not be found.');
                }
                $request->attributes->set('dispatchConfirmation', $dispatchConfirmation);
                break;
            case 'getConsignmentLabel':
                $consignment = $this->consignmentRepository->getConsignment($request->get('consignmentId'));
                if (! $consignment) {
                    throw new NotFoundResourceException('Required Consignment could not be found.');
                }

                $request->attributes->set('consignment', $consignment);
                break;
        }
    }

    /**
     * @param Request $request
     * @return \Webit\Shipment\Consignment\DispatchConfirmationInterface
     */
    private function getDispatchConfirmation(Request $request)
    {
        $vendor = $this->vendorRepository->getVendor($request->get('vendorCode'));
        if (! $vendor) {
            throw new ResourceNotFoundException('Required Vendor "%s" could not be found.', $request->get('vendorCode'));
        }

        $number = $request->get('number');
        $dispatchConfirmation = $this->dispatchConfirmationRepository->getDispatchConfirmation($vendor, $number);

        return $dispatchConfirmation;
    }
}
