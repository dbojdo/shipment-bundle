<?php

namespace Webit\Bundle\ShipmentBundle\Command;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Webit\Shipment\Consignment\ConsignmentInterface;
use Webit\Shipment\Consignment\ConsignmentRepositoryInterface;
use Webit\Shipment\Manager\ConsignmentManagerInterface;

final class SyncOpenedConsignmentsStatusesCommand extends Command
{
    /** @var ConsignmentManagerInterface */
    private $consignmentManager;

    /** @var ConsignmentRepositoryInterface */
    private $consignmentRepository;

    public function __construct(
        ConsignmentManagerInterface $consignmentManager,
        ConsignmentRepositoryInterface $consignmentRepository
    ) {
        parent::__construct();
        $this->consignmentManager = $consignmentManager;
        $this->consignmentRepository = $consignmentRepository;
    }

    protected function configure()
    {
        parent::configure();
        $this->setName('webit-shipment:sync:opened-consignments-statuses')
            ->setDescription('Sync statuses of opened consignments.');

        $this->addArgument('from', InputArgument::OPTIONAL, 'Date from');
        $this->addArgument('to', InputArgument::OPTIONAL, 'Date to');
    }

    /**
     * @inheritdoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        list($dateFrom, $dateTo) = $this->extractDates($input);

        $consignments = $this->consignmentRepository->getOpenedConsignments($dateFrom, $dateTo);

        $report = $this->initReport($consignments);

        $this->consignmentManager->synchronizeConsignmentsStatus($consignments);

        $report = $this->updateReport($report, $consignments);

        $this->printReport($report, $output);

        return 0;
    }

    /**
     * @param InputInterface $input
     * @return array
     */
    private function extractDates(InputInterface $input)
    {
        $dateFrom = $input->getArgument('from');
        $dateFrom = $dateFrom ? \DateTime::createFromFormat('Y-m-d', $dateFrom) : date_create('now - 14 days');


        $dateTo = $input->getArgument('to');
        $dateTo = $dateTo ? \DateTime::createFromFormat('Y-m-d', $dateTo) : new \DateTime();

        return array($dateFrom, $dateTo);
    }

    /**
     * @param ArrayCollection|ConsignmentInterface[] $consignments
     * @return array
     */
    private function initReport(ArrayCollection $consignments)
    {
        $report = array();
        foreach ($consignments as $consignment) {
            $consignmentReport = array('consignment' => $consignment, 'previous' => $consignment->getStatus(), 'current' => $consignment->getStatus());
            foreach ($consignment->getParcels() as $parcel) {
                $consignmentReport['parcels'][$parcel->getNumber()] = array($parcel->getStatus(), $parcel->getStatus());
            }

            $report[$consignment->getId()] = $consignmentReport;
        }

        return $report;
    }

    /**
     * @param array $report
     * @param ArrayCollection|ConsignmentInterface[] $consignments
     * @return array
     */
    private function updateReport(array $report, ArrayCollection $consignments)
    {
        foreach ($consignments as $consignment) {
            $report[$consignment->getId()]['current'] = $consignment->getStatus();
            foreach ($consignment->getParcels() as $parcel) {
                $report[$consignment->getId()]['parcels'][$parcel->getNumber()][1] = $parcel->getStatus();
            }
        }

        return $report;
    }

    /**
     * @param array $report
     * @param OutputInterface $output
     */
    private function printReport(array $report, OutputInterface $output)
    {
        foreach ($report as $entry) {
            /** @var ConsignmentInterface $consignment */
            $consignment = $entry['consignment'];
            $output->writeln(
                sprintf(
                    'Consignment <info>%s:%s</info> (ID: <info>%s</info>): <info>%s</info> -> <info>%s</info>',
                    $consignment->getVendor(),
                    $consignment->getVendorId(),
                    $consignment->getId(),
                    $entry['previous'],
                    $entry['current']
                )
            );
            foreach ($entry['parcels'] as $waybill => $parcelEntry) {
                $output->writeln(
                    sprintf(
                        '  Parcel <info>%s</info>: <info>%s</info> -> <info>%s</info>',
                        $waybill,
                        $parcelEntry[0],
                        $parcelEntry[1]
                    )
                );
            }
            $output->writeln('');
        }
    }
}
