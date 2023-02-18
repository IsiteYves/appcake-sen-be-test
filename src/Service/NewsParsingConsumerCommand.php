<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use App\Service\NewsParsingConsumerService;

class NewsParsingConsumerCommand extends Command
{
    private $consumerService;

    public function __construct(NewsParsingConsumerService $consumerService)
    {
        $this->consumerService = $consumerService;
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('news:parsing:consumer')
            ->setDescription('Starts the news parsing consumer');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->consumerService->consume();
    }
}
