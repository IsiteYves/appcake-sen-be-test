<?php

namespace App\Service;

use OldSound\RabbitMqBundle\RabbitMq\ConsumerInterface;
use PhpAmqpLib\Message\AMQPMessage;
use App\Service\NewsParsingService;

class NewsParsingConsumerService implements ConsumerInterface
{
    private $parsingService;

    public function __construct(NewsParsingService $parsingService)
    {
        $this->parsingService = $parsingService;
    }

    public function execute(AMQPMessage $msg)
    {
        $newsUrl = $msg->body;
        // Call your parsing function here, passing in $newsUrl as the argument
        // Example: $this->parsingService->parseNews($newsUrl);
        // Remember to catch any exceptions that might occur during parsing
        // You can also log the result of the parsing operation using a logger service
    }
}
