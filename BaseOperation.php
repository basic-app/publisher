<?php
/**
 * @author Basic App Dev Team
 * @license MIT
 * @link https://basic-app.com
 */
namespace BasicApp\Publisher;

use Psr\Log\LoggerInterface;
use Psr\Log\LoggerTrait;
use Psr\Log\LoggerAwareTrait;

abstract class BaseOperation implements OperationInterface
{

    use LoggerAwareTrait;
    use LoggerTrait;

    public function __construct(LoggerInterface $logger)
    {
        $this->setLogger($logger);
    }

    public function log($level, $message, array $context = array())
    {
        $this->logger->log($level, $message, $context);
    }

    abstract public function run();

}