<?php

namespace Webit\Bundle\ShipmentBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Webit\Shipment\Consignment\ConsignmentRepositoryInterface;
use Webit\Shipment\Consignment\ConsignmentStatusList;
use Webit\Shipment\Manager\ConsignmentManagerInterface;

final class ChangeConsignmentStatusCommand extends Command
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
        $this->setName('webit-shipment:consignment:change-status')
            ->setDescription('Sync statuses of opened consignments.');

        $this->addArgument('consignmentId', InputArgument::REQUIRED, 'Consignment ID');
        $this->addArgument(
            'status',
            InputArgument::REQUIRED,
            sprintf('Status: "%s"', implode('", "', ConsignmentStatusList::getStatusList()))
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $consignment = $this->consignmentRepository->getConsignment(
            $consignmentId = $input->getArgument('consignmentId')
        );

        if (!$consignment) {
            $output->writeln(sprintf('<error>Could not find the consignment of ID "%s".</error>', $consignmentId));
            return 1;
        }

        $this->consignmentManager->changeConsignmentStatus($consignment, $status = $input->getArgument('status'));

        $output->writeln(
            'The status of consignment of ID <info>"%s"</info> has been changed from <info>"%s"</info> to <info>"%s"</info>.'
        );

        return 0;
    }
}