<?php
/**
 * @author Basic App Dev Team
 * @license MIT
 * @link https://basic-app.com
 */
namespace BasicApp\Publisher\Operations;

use ZipArchive;

class SetPermissionsOperation extends \BasicApp\Publisher\BaseOperation
{

    public $path;

    public $permissions;

    public function __construct(string $path, string $permissions)
    {
        parent::__construct();

        $this->path = $path;

        $this->permissions = $permissions;
    }

    public function run()
    {
        if (!is_file($this->path) && !is_dir($this->path))
        {
            if (is_link($this->path))
            {
                $this->logger->error('Can\'t set {permissions} permissions to symlink {path}.', [
                    'path' => $this->path,
                    'permissions' => $this->permissions
                ]);

                return;
            }
                
            $this->logger->error('Can\'t set permissions {permissions} to {path}. Path not found.', [
                'path' => $this->path,
                'permissions' => $this->permissions
            ]);

            return;
        }

        if (!chmod($this->path, is_string($this->permissions) ? octdec($this->permissions) : $this->permissions))
        {
            $this->logger->error('{path} permissions {permissions} is not changed.', [
                'path' => $path,
                'permissions' => $this->permissions
            ]);

            return;
        }

        $this->logger->info('Permissions {permissions} was applied to {path}.', [
            'path' => $this->path,
            'permissions' => $this->permissions
        ]);
    }

}