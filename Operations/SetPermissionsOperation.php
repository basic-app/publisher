<?php
/**
 * @author Basic App Dev Team
 * @license MIT
 * @link https://basic-app.com
 */
namespace BasicApp\Publisher\Operations;

use ZipArchive;
use Psr\Log\LoggerInterface;
use BasicApp\Publisher\PublisherException;

class SetPermissionsOperation extends \BasicApp\Publisher\BaseOperation
{

    public $path;

    public $permissions;

    public function __construct(LoggerInterface $logger, string $path, string $permissions)
    {
        parent::__construct($logger);

        $this->path = $path;

        $this->permissions = $permissions;
    }

    public function run()
    {
        if (!is_file($this->path) && !is_dir($this->path))
        {
            $this->error('{path} not found.', [
                'path' => $this->path,
                'permissions' => $this->permissions
            ]);

            return;
        }

        if (!chmod($this->path, is_string($this->permissions) ? octdec($this->permissions) : $this->permissions))
        {
            $this->error('{path} permissions {permissions} is not changed.', [
                'path' => $path,
                'permissions' => $this->permissions
            ]);

            return;
        }

        $this->info('Permissions {permissions} was applied to {path}.', [
            'path' => $this->path,
            'permissions' => $this->permissions
        ]);
    }

}