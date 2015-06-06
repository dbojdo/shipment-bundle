<?php
/**
 * File PrintController.php
 * Created at: 2015-03-16 06-07
 *
 * @author Daniel Bojdo <daniel.bojdo@web-it.eu>
 */

namespace Webit\Bundle\ShipmentBundle\Controller;

use Webit\Shipment\Consignment\ConsignmentInterface;
use Webit\Shipment\Consignment\DispatchConfirmationInterface;
use Webit\Shipment\Manager\PrintManagerInterface;

class PrintController
{

    /**
     * @var PrintManagerInterface
     */
    private $printManager;

    public function __construct(PrintManagerInterface $printManager)
    {
        $this->printManager = $printManager;
    }

    public function getDispatchConfirmationReceipt(DispatchConfirmationInterface $dispatchConfirmation, $mode = 'show')
    {
        $receipt = $this->printManager->getDispatchConfirmationReceipt($dispatchConfirmation);
        $consignment = $dispatchConfirmation->getConsignments()->first();
        $vendor = $consignment->getVendor();

        $filename = sprintf('%s_%s.pdf', $vendor->getCode(), $dispatchConfirmation->getNumber());

        return $this->createResponse($receipt, $filename, $mode);
    }

    public function getDispatchConfirmationLabels(DispatchConfirmationInterface $dispatchConfirmation, $mode = 'show')
    {
        $labels = $this->printManager->getDispatchConfirmationLabels($dispatchConfirmation);
        $consignment = $dispatchConfirmation->getConsignments()->first();
        $vendor = $consignment->getVendor();

        $filename = sprintf('%s_%s_labels.pdf', $vendor->getCode(), $dispatchConfirmation->getNumber());

        return $this->createResponse($labels, $filename, $mode);
    }

    public function getConsignmentLabel(ConsignmentInterface $consignment, $mode = 'show')
    {
        $label = $this->printManager->getConsignmentLabel($consignment);
        $vendor = $consignment->getVendor();

        $filename = sprintf('%s_%s_label.pdf', $vendor->getCode(), $consignment->getVendorId());

        return $this->createResponse($label, $filename, $mode);
    }

    private function createResponse(\SplFileInfo $file, $filename, $mode)
    {
        $response = new Response();
        $response->setContent(file_get_contents($file->getPathname()));

        $response->headers->set('Content-Type', 'application/pdf; charset=binary',true);
        $response->headers->set('Content-Disposition', ($mode =='download' ? 'attachment' : 'inline').'; filename='.$filename,true);

        return $response;
    }
}