<?php
/**
 * @author Basic App Dev Team
 * @license MIT
 * @link https://basic-app.com
 */
namespace BasicApp\Publisher\Events;

use Closure;
use BasicApp\Publisher\OperationInterface;
use BasicApp\Publisher\Operations\UnzipOperation;
use BasicApp\Publisher\Operations\DownloadOperation;
use BasicApp\Publisher\Operations\SetPermissionsOperation;
use BasicApp\Publisher\Operations\DeleteOperation;
use BasicApp\Publisher\Operations\CopyOperation;

class PublishEvent extends \BasicApp\Event\BaseEvent
{

    public $refresh = false;

    protected $_operations = [];

    public function createOperation(string $class, ...$params) : OperationInterface
    {
        return (new $class(...$params))->setLogger($this->logger);
    }

    public function addOperation(OperationInterface $operation)
    {
        $this->_operations[] = $operation;

        return $operation;
    }

    public function getOperations()
    {
        return $this->_operations;
    }

    public function unzip(...$args) : UnzipOperation
    {
        return $this->addOperation($this->createOperation(UnzipOperation::class, ...$args));
    }

    public function download(...$args) : DownloadOperation
    {
        return $this->addOperation($this->createOperation(DownloadOperation::class, ...$args));
    }

    public function setPermissions(...$args) : SetPermissionsOperation
    {
        return $this->addOperation($this->createOperation(SetPermissionsOperation::class, ...$args));
    }

    public function delete(...$args) : DeleteOperation
    {
        return $this->addOperation($this->createOperation(DeleteOperation::class, ...$args));
    }

    public function copy(...$args) : CopyOperation
    {
        return $this->addOperation($this->createOperation(CopyOperation::class, ...$args));
    }

}