<?php

namespace App\Service;

use OldSound\RabbitMqBundle\RabbitMq\Producer;

class NewsParsingService extends Producer
{
    public function addParsingTask($newsUrl)
    {
        $this->publish($newsUrl);
    }
}
