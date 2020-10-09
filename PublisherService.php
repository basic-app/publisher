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

    public function downloadIfNotExists(string $url, string $target, array $curlOptions = [])
    {
        if ($this->isExists($target))
        {
            $this->logger->debug('download: {target} exists', [
                'url' => $url,
                'target' => $target
            ]);

            return false;
        }

        return $this->download($url, $target, $curlOptions);
    }    

    public function download(string $url, string $target, array $curlOptions = [])
    {
        $permissions = '0755';

        $recursive = true;

        if (!$this->createDirectory(dirname($target), $permissions, $recursive))
        {
            return false;
        }

        $curl = service('curl');

        $curl->setLogger($this->logger);

        if (!$curl->download($url, $target, $curlOptions))
        {
            return false;
        }

        $this->logger->info('download: {url} -> {target}', [
            'url' => $url,
            'target' => $target
        ]);

        return true;
    }

    public function unzip(string $source, string $target, array $entries = [])
    {
        if (!is_file($source))
        {
            $this->logger->error('unzip: {source} not found', [
                'source' => $source
            ]);

            return false;
        }

        $zip = new ZipArchive;
        
        if ($zip->open($source) !== true)
        {
            $this->logger->error('unzip: {source} open error', [
                'source' => $source
            ]);

            return false;
        }

        if (!$zip->extractTo($target, (count($entries) > 0) ? $entries : null))
        {
            $this->logger->error('unzip: {source} -> {target} extract error', [
                'source' => $source,
                'target' => $target
            ]);

            return false;
        }
        
        if (!$zip->close())
        {
            $this->logger->error('unzip: {source} close error', [
                'source' => $source
            ]);

            return false;
        }

        $this->logger->info('unzip: {source} -> {target}', [
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
                $this->logger->error('chmod: Can\'t set to symlink {path}', [
                    'path' => $path,
                    'permissions' => $permissions
                ]);

                return false;
            }
                
            $this->logger->error('chmod: Path not found {path}', [
                'path' => $path,
                'permissions' => $permissions
            ]);

            return false;
        }

        if (!chmod($path, is_string($permissions) ? octdec($permissions) : $permissions))
        {
            $this->logger->error('chmod: {path} chmod error', [
                'path' => $path,
                'permissions' => $permissions
            ]);

            return false;
        }

        $this->logger->info('chmod: {permissions} was applied to {path}', [
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
            $this->logger->debug('mkdir: {path} exists', [
                'path' => $path
            ]);

            return true;
        }

        if (!mkdir($path, $permissions, $recursive))
        {
            $this->logger->error('mkdir: {path} mkdir error', [
                'path' => $path
            ]);

            return false;
        }

        $this->logger->info('mkdir: {path}', [
            'path' => $path
        ]);

        return true;
    }

    public function getDirectoryName(string $path) : string
    {
        $directory = pathinfo($path, PATHINFO_DIRNAME);

        if (!$directory)
        {        
            return false;
        }

        return $directory;
    }    

    public function copy(string $source, string $target, bool $overwrite = true, bool $hidden = true)
    {
        if (!$overwrite && $this->isExists($target))
        {
            $this->logger->debug('copy: {target} exists', [
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

        $this->logger->error('copy: {source} not found', [
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
            $this->logger->error('symlink: {source} readlink error', [
                'source' => $source,
                'target' => $target
            ]);

            return false;
        }

        if (!symlink($link, $target))
        {
            $this->logger->error('symlink: {source} -> {target} symlink error', [
                'source' => $source,
                'target' => $target
            ]);

            return false;
        }

        $this->logger->info('symlink: {source} -> {target}', [
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
            $this->logger->error('copy: Can\'t get directory name from {source}', [
                'source' => $source
            ]);

            return false;
        }

        if (!$this->createDirectory($targetDirectory))
        {
            return false;
        }

        if (!copy($source, $target))
        {
            $this->logger->error('copy: {source} -> {target} copy error', [
                'source' => $source,
                'target' => $target
            ]);

            return false;
        }

        $this->logger->info('copy: {source} -> {target}', [
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

        $return = true;

        foreach(directory_map($source, 1, $hidden) as $file)
        {
            $file = rtrim($file, DIRECTORY_SEPARATOR);

            $from = $source . DIRECTORY_SEPARATOR . $file;

            $to = $target . DIRECTORY_SEPARATOR . $file;

            if (!$this->copy($from, $to, $overwrite, $hidden))
            {
                $return = false;
            }
        }

        return $return;
    }

    public function deleteIfExists(string $path)
    {
        if (!$this->isExists($path))
        {
            $this->logger->debug('delete: {path} exists', [
                'path' => $path
            ]);

            return false;
        }

        return $this->delete($path);
    }

    public function delete(string $path)
    {
        if (is_file($path))
        {
            if (!unlink($path))
            {
                $this->logger->error('unlink: {path} error', [
                    'path' => $path
                ]);

                return false;
            }

            $this->logger->info('unlink: {path}', [
                'path' => $path
            ]);

            return true;
        }

        if (is_link($path))
        {
            if (!unlink($path))
            {
                $this->logger->error('unlink: {path} error', [
                    'path' => $path
                ]);

                return false;
            }

            $this->logger->info('unlink: {path}', [
                'path' => $path
            ]);

            return true;
        }

        if (!is_dir($path))
        {
            $this->logger->error('delete: {path} not found', [
                'path' => $path
            ]);

            return false;
        }

        $hidden = true;

        $return = true;

        foreach(directory_map($path, 1, $hidden) as $file)
        {
            $file = rtrim($file, DIRECTORY_SEPARATOR);

            if (!$this->delete($path . DIRECTORY_SEPARATOR . $file))
            {
                $return = false;
            }
        }

        if (!$return)
        {
            return false;
        }

        if (!rmdir($path))
        {
            $this->logger->error('rmdir: {path} error', [
                'path' => $path
            ]);

            return false;
        }

        $this->logger->info('rmdir: {path}', [
            'path' => $path
        ]);

        return true;
    }

}