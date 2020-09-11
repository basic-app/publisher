<?php
/**
 * @author Basic App Dev Team
 * @license MIT
 * @link https://basic-app.com
 */
namespace BasicApp\Publisher\Operations;

class CreateDirectoryOperation extends \BasicApp\Publisher\BaseOperation
{

    public $path;

    public $permissions;

    public $recursive;

    public function __construct(string $path, $permissions = '0777', bool $recursive = true)
    {
        parent::__construct();

        $this->path = $path;

        $this->permissions = is_string($permissions) ? octdec($permissions) : $permissions;

        $this->recursive = $recursive;
    }

    public function run()
    {
        if ($this->isExists($this->path))
        {
            $this->logger->info('{path} is exits.', [
                'path' => $this->path
            ]);

            return;
        }

        if (!mkdir($this->path, $this->permissions, $this->recursive))
        {
            $this->logger->error('{path} is not created.', [
                'path' => $this->path
            ]);

            return;
        }

        $this->logger->info('{path} created.', [
            'path' => $this->path
        ]);
    }

}