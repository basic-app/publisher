<?php
/**
 * @author Basic App Dev Team
 * @license MIT
 * @link https://basic-app.com
 */
namespace BasicApp\Publisher;

abstract class BaseOperation implements OperationInterface
{

    abstract public function run();

}