<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;
use App\Service\NewsParsingService;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\News;
use Symfony\Component\Console\Helper\ProgressBar;

class NewsParsingCommand extends Command
{
    protected static $defaultName = 'news:parsing:start';
    private $parsingService;
    private $em;

    public function __construct(NewsParsingService $parsingService, EntityManagerInterface $em)
    {
        $this->parsingService = $parsingService;
        $this->em = $em;
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setDescription('Starts the news parsing process')
            ->addOption('url', 'u', InputOption::VALUE_REQUIRED, 'The URL of the news resource to parse');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $url = $input->getOption('url');
        if (!$url) {
            $output->writeln('Please specify the URL of the news resource to parse using the --url option');
            return;
        }

        $output->writeln('Starting news parsing process...');
        $this->parseNewsResource($url, $output);
        $output->writeln('News parsing process completed');
    }

    private function parseNewsResource($url, $output)
    {
        // Parse the news resource using your own parsing function
        $articles = $this->parsingService->parseNewsResource($url);
        $progressBar = new ProgressBar($output, count($articles));

        foreach ($articles as $article) {
            $existingNews = $this->em->getRepository(News::class)->findOneBy(['title' => $article['title']]);
            if ($existingNews) {
                $existingNews->setLastUpdated(new \DateTime());
            } else {
                $news = new News();
                $news->setTitle($article['title']);
                $news->setDescription($article['description']);
                $news->setImageUrl($article['image']);
                $news->setDateAdded(new \DateTime());
                $news->setLastUpdated(new \DateTime());
                $this->em->persist($news);
            }

            $progressBar->advance();
            $this->parsingService->addParsingTask($article['url']);
        }

        $this->em->flush();
        $progressBar->finish();
        $output->writeln('');
    }
}
