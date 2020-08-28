<?php

namespace BasicApp\Publisher;

use Psr\Log\LoggerTrait;
use Psr\Log\AbstractLogger;
use Psr\Log\LogLevel;
use CodeIgniter\CLI\CLI;

class PublisherLogger extends AbstractLogger
{

    use LoggerTrait;

    public function log($level, $message, array $context = [])
    {
        $replace = [];

        foreach($context as $key => $value)
        {
            if (!is_array($value))
            {
                $replace['{' . $key . '}'] = CLI::color($value, 'blue');
            }
        }

        $message = strtr($message, $replace);

        CLI::write($message);
    }

}