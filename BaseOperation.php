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

    use LoggerAwareTrait;

    public function __construct()
    {
        $this->setLogger(new NullLogger);
    }

    abstract public function run();

    protected function pathIsExists(string $path)
    {
        return is_file($path) || is_dir($path) || is_symlink($path);
    }

}