<?php
/**
 * @author Basic App Dev Team
 * @license MIT
 * @link https://basic-app.com
 */
namespace BasicApp\Publisher;

use Psr\Log\LoggerAwareTrait;
use Psr\Log\NullLogger;

abstract class BaseOperation implements OperationInterface
{

    use LoggerAwareTrait {
        setLogger as setLoggerTrait;
    }

    public function __construct()
    {
        $this->setLogger(new NullLogger);
    }

    abstract public function run() : bool;

    public function isExists(string $path) : bool
    {
        clearstatcache();

        return is_file($path) || is_dir($path) || is_link($path);
    }

    public function setLogger($logger)
    {
        $this->setLoggerTrait($logger);

        return $this;
    }

}