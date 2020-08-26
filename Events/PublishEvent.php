<?php
/**
 * @author Basic App Dev Team
 * @license MIT
 * @link https://basic-app.com
 */
namespace BasicApp\Publisher\Events;

use Closure;

class PublishEvent extends \BasicApp\Event\BaseEvent
{

    const DELETE_DIRECTORY = 'DELETE_DIRECTORY';
    const DELETE_FILE = 'DELETE_FILE';
    const DOWNLOAD = 'DOWNLOAD';
    const UNZIP = 'UNZIP';
    const COPY_DIRECTORY = 'COPY_DIRECTORY';
    const COPY_FILE = 'COPY_FILE';
    const DIRECTORY_PERMISSIONS = 'DIRECTORY_PERMISSIONS';
    const FILE_PERMISSIONS = 'FILE_PERMISSIONS';

    protected $_config = [
        self::DELETE_DIRECTORY => [],
        self::DELETE_FILE => [],
        self::DOWNLOAD => [],
        self::UNZIP => [],
        self::COPY_DIRECTORY => [],
        self::COPY_FILE => [],
        self::DIRECTORY_PERMISSIONS => [],
        self::FILE_PERMISSIONS => [],
        self::BEFORE_PUBLISH => [],
        self::AFTER_PUBLISH => []
    ];

    public function __construct(array $config = [])
    {
        parent::__construct();
    }

    public function toArray() : array
    {
        return $this->_config;
    }    

    public function copyDirectory($source, $target, $recursive = true, $overwrite = true, $permissions = null)
    {
        $this->_config[static::COPY_DIRECTORY][] = [
            'source' => $source,
            'target' => $target,
            'recursive' => $recursive,
            'permissions' => $permissions,
            'overwrite' => $overwrite
        ];
    }

    public function copyFile($source, $target, $overwrite = true, $permissions = null)
    {
        $this->_config[static::COPY_FILE][] = [
            'source' => $source,
            'target' => $target,
            'permissions' => $permissions,
            'overwrite' => $overwrite
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

    public function deleteDirectory($path, $keepRootDirectory = false)
    {
        $this->_config[static::DELETE_DIRECTORY][] = [
            'path' => $path,
            'keepRootDirectory' => $keepRootDirectory
        ];
    }

    public function deleteFile($path)
    {
        $this->_config[static::DELETE_FILE][] = [
            'path' => $path
        ];        
    }

    public function download($url, $overwrite = true)
    {
        $this->_config[static::DOWNLOAD][] = [
            'url' => $url,
            'overwrite' => $overwrite
        ];        
    }

    public function unzip($file, $target, $overwrite = true)
    {
        $this->_config[static::UNZIP][] = [
            'file' => $file,
            'target' => $target, 
            'overwrite' => $overwrite
        ];
    }

    public function deleteFile($path)
    {
        $this->_config[static::DELETE_FILE][] = [
            'path' => $path
        ];
    }   

}