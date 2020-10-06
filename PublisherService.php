<?php
/**
 * @author Basic App Dev Team
 * @license MIT
 * @link https://basic-app.com
 */
namespace BasicApp\Publisher;

use ZipArchive;

class PublisherService extends \BasicApp\Service\BaseService
{

    public function isExists(string $path) : bool
    {
        clearstatcache();

        return is_file($path) || is_dir($path) || is_link($path);
    }

    public function download(string $url, string $target, bool $overwrite = true, array $curlOptions = [])
    {
        if ($this->isExists($target) && !$overwrite)
        {
            $this->logger->debug('{url} -> {target} exists', [
                'url' => $url,
                'target' => $target
            ]);

            return true;
        }

        $permissions = '0755';

        $recursive = true;

        if (!$this->createDirectory(dirname($target), $permissions, $recursive))
        {
            return false;
        }

        $curl = service('curl');

        $curl->setLogger($this->logger);

        if (!$curl->download($url, $target, $overwrite, $curlOptions))
        {
            return false;
        }

        $this->logger->info('{url} -> {target}', [
            'url' => $url,
            'target' => $target
        ]);

        return true;
    }

    public function unzip(string $source, string $target, array $entries = [])
    {
        if (!is_file($source))
        {
            $this->logger->error('{source} not found.', [
                'source' => $source
            ]);

            return false;
        }

        $zip = new ZipArchive;
        
        if ($zip->open($source) !== true)
        {
            $this->logger->error('{source} open error.', [
                'source' => $source
            ]);

            return false;
        }

        if (!$zip->extractTo($target, (count($entries) > 0) ? $entries : null))
        {
            $this->logger->error('{source} extract to {target} error.', [
                'source' => $source,
                'target' => $target
            ]);

            return false;
        }
        
        if (!$zip->close())
        {
            $this->logger->error('{source} close error.', [
                'source' => $source
            ]);

            return false;
        }

        $this->logger->info('{source} -> {target}.', [
            'source' => $source,
            'target' => $target,
            'entries' => $entries
        ]);

        return true;
    }

    public function setPermissions(string $path, string $permissions)
    {
        if (!is_file($path) && !is_dir($path))
        {
            if (is_link($path))
            {
                $this->logger->error('Can\'t set {permissions} permissions to symlink {path}.', [
                    'path' => $path,
                    'permissions' => $permissions
                ]);

                return false;
            }
                
            $this->logger->error('Can\'t set permissions {permissions} to {path}. Path not found.', [
                'path' => $path,
                'permissions' => $permissions
            ]);

            return false;
        }

        if (!chmod($path, is_string($permissions) ? octdec($permissions) : $permissions))
        {
            $this->logger->error('{path} permissions {permissions} is not changed.', [
                'path' => $path,
                'permissions' => $permissions
            ]);

            return false;
        }

        $this->logger->info('Permissions {permissions} was applied to {path}.', [
            'path' => $path,
            'permissions' => $permissions
        ]);

        return true;
    }

    public function createDirectory(string $path, $permissions = '0777', bool $recursive = true)
    {
        $permissions = is_string($permissions) ? octdec($permissions) : $permissions;

        if ($this->isExists($path))
        {
            $this->logger->debug('{path} exists', [
                'path' => $path
            ]);

            return true;
        }

        if (!mkdir($path, $permissions, $recursive))
        {
            $this->logger->error('{path} mkdir error', [
                'path' => $path
            ]);

            return false;
        }

        $this->logger->info('{path} created', [
            'path' => $path
        ]);

        return true;
    }

    public function getDirectoryName(string $path) : string
    {
        $directory = pathinfo($path, PATHINFO_DIRNAME);

        if (!$directory)
        {
            $this->logger->error('Can\'t get directory name from {path}.', [
                'path' => $path
            ]);
        
            return false;
        }

        return $directory;
    }    

    public function copy(string $source, string $target, bool $overwrite = true, bool $hidden = true)
    {
        if (!$overwrite && $this->isExists($target))
        {
            $this->logger->debug('{source} -> {target} exists', [
                'source' => $source,
                'target' => $target,
                'overwrite' => $overwrite
            ]);

            return true;
        }

        clearstatcache();

        if (is_link($source))
        {
            return $this->copyLink($source, $target);
        }
        elseif(is_dir($source))
        {
            return $this->copyDirectory($source, $target, $overwrite, $hidden);
        }
        elseif(is_file($source))
        {
            return $this->copyFile($source, $target);
        }

        $this->logger->error('{source} -> {target } source error', [
            'source' => $source,
            'target' => $target,
            'overwrite' => $overwrite
        ]);

        return false;
    }

    public function copyLink(string $source, string $target)
    {
        $link = readlink($source);

        if ($link === false)
        {
            $this->logger->error('{source} -> {target} read error', [
                'source' => $source,
                'target' => $target
            ]);

            return false;
        }

        if (!symlink($link, $target))
        {
            $this->logger->error('{source} -> {target} symlink error', [
                'source' => $source,
                'target' => $target
            ]);

            return false;
        }

        $this->logger->info('{source} -> {target}', [
            'source' => $source,
            'target' => $target
        ]);

        return true;
    }

    public function copyFile(string $source, string $target)
    {
        $targetDirectory = $this->getDirectoryName($target);

        if (!$targetDirectory)
        {
            return false;
        }

        if (!$this->createDirectory($targetDirectory))
        {
            return false;
        }

        if (!copy($source, $target))
        {
            $this->logger->error('{source} -> {target} copy error', [
                'source' => $source,
                'target' => $target
            ]);

            return false;
        }

        $this->logger->info('{source} -> {target}', [
            'source' => $source,
            'target' => $target
        ]);

        return true;
    }

    public function copyDirectory(string $source, string $target, bool $overwrite = true, bool $hidden = true)
    {
        helper('filesystem');

        if (!$this->createDirectory($target))
        {
            return false;
        }

        $items = directory_map($source, 1, $hidden);

        foreach($items as $file)
        {
            $file = rtrim($file, DIRECTORY_SEPARATOR);

            $from = $source . DIRECTORY_SEPARATOR . $file;

            $to = $target . DIRECTORY_SEPARATOR . $file;

            return $this->copy($from, $to, $overwrite, $hidden);
        }

        return true;
    }    

}