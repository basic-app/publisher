<?php
/**
 * @author Basic App Dev Team
 * @license MIT
 * @link https://basic-app.com
 */
namespace BasicApp\Publisher;

use Psr\Log\LoggerAwareInterface;

interface OperationInterface extends LoggerAwareInterface
{

    public function __construct();

    public function run();

}