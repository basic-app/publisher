<?php

namespace BasicApp\Publisher\Events;

class PublishEvent extends \BasicApp\Event\BaseEvent
{

    const COPY_DIRECTORY = 'COPY_DIRECTORY';

    const COPY_FILE = 'COPY_FILE';

    const DIRECTORY_PERMISSIONS = 'DIRECTORY_PERMISSIONS';

    const FILE_PERMISSIONS = 'FILE_PERMISSIONS';

    protected $_config = [
        self::COPY_DIRECTORY => [],
        self::COPY_FILE => [],
        self::DIRECTORY_PERMISSIONS => [],
        self::FILE_PERMISSIONS => []
    ];

    public function __construct(array $config = [])
    {
        parent::__construct();
    }

    public function copyDirectory($source, $target, $recursive = true, $permissions = null)
    {
        $this->_config[static::COPY_DIRECTORY][] = [
            'source' => $source,
            'target' => $target,
            'recursive' => $recursive,
            'permissions' => $permissions
        ];
    }

    public function copyFile($source, $target, $permissions = true)
    {
        $this->_config[static::COPY_FILE][] = [
            'source' => $source,
            'target' => $target,
            'permissions' => $permissions
        ];
    }

    public function filePermissions($path, $permisions, $recursive = false)
    {
        $this->_config[static::FILE_PERMISSIONS][] = [
            'path' => $path,
            'permission' => $permissions,
            'recursive' => $recursive
        ];    
    }

    public function directoryPermissions($path, $permisions, bool $recursive = false)
    {
        $this->_config[static::DIRECTORY_PERMISSIONS][] = [
            'path' => $path,
            'permission' => $permissions,
            'recursive' => $recursive
        ];
    }

    public function toArray()
    {
        return $this->config;
    }

}