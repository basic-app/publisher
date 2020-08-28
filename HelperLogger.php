<?php

namespace BasicApp\Publisher;

use Psr\Log\LoggerTrait;
use Psr\Log\LogLevel;

class HelperLogger extends \Psr\Log\AbstractLogger
{

    use LoggerTrait;

    const LEVELS = [
        LogLevel::EMERGENCY,
        LogLevel::ALERT,
        LogLevel::CRITICAL,
        LogLevel::ERROR
    ];

    public function log($level, $message, array $context = [])
    {
        if (array_search($level, static::LEVELS) !== false)
        {
            throw new PublisherException($message, $context);
        }

        // noop
    }

}