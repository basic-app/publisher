<?php
/**
 * @author Basic App Dev Team
 * @license MIT
 * @link https://basic-app.com
 */
namespace BasicApp\Publisher;

use Psr\Log\LoggerInterface;

interface OperationInterface
{

    public function throwException(string $message, array $context = []);

    public function run(LoggerInterface $logger);

}