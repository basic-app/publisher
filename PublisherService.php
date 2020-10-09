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
            $this->logger->debug('Download: {target} exists', [
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

        $this->logger->info('Download: {url} -> {target}', [
            'url' => $url,
            'target' => $target
        ]);

        return true;
    }

    public function unzip(string $source, string $target, array $entries = [])
    {
        if (!is_file($source))
        {
            $this->logger->error('Unzip: {source} not found', [
                'source' => $source
            ]);

            return false;
        }

        $zip = new ZipArchive;
        
        if ($zip->open($source) !== true)
        {
            $this->logger->error('Unzip: {source} open error', [
                'source' => $source
            ]);

            return false;
        }

        if (!$zip->extractTo($target, (count($entries) > 0) ? $entries : null))
        {
            $this->logger->error('Unzip: {source} -> {target} extract error', [
                'source' => $source,
                'target' => $target
            ]);

            return false;
        }
        
        if (!$zip->close())
        {
            $this->logger->error('Unzip: {source} close error', [
                'source' => $source
            ]);

            return false;
        }

        $this->logger->info('Unzip: {source} -> {target}', [
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
                $this->logger->error('Set permissions: Can\'t set to symlink {path}', [
                    'path' => $path,
                    'permissions' => $permissions
                ]);

                return false;
            }
                
            $this->logger->error('Set permissions: Path not found {path}', [
                'path' => $path,
                'permissions' => $permissions
            ]);

            return false;
        }

        if (!chmod($path, is_string($permissions) ? octdec($permissions) : $permissions))
        {
            $this->logger->error('Set permissions: {path} chmod error', [
                'path' => $path,
                'permissions' => $permissions
            ]);

            return false;
        }

        $this->logger->info('Set permissions: {permissions} was applied to {path}', [
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
            $this->logger->debug('Create directory: {path} exists', [
                'path' => $path
            ]);

            return true;
        }

        if (!mkdir($path, $permissions, $recursive))
        {
            $this->logger->error('Create directory: {path} mkdir error', [
                'path' => $path
            ]);

            return false;
        }

        $this->logger->info('Create directory: {path}', [
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
            $this->logger->debug('Copy: {target} exists', [
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

        $this->logger->error('Copy: {source} not found', [
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
            $this->logger->error('Copy link: {source} readlink error', [
                'source' => $source,
                'target' => $target
            ]);

            return false;
        }

        if (!symlink($link, $target))
        {
            $this->logger->error('Copy link: {source} -> {target} symlink error', [
                'source' => $source,
                'target' => $target
            ]);

            return false;
        }

        $this->logger->info('Copy link: {source} -> {target}', [
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
            $this->logger->error('Copy file: Can\'t get directory name from {source}', [
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
            $this->logger->error('Copy file: {source} -> {target} copy error', [
                'source' => $source,
                'target' => $target
            ]);

            return false;
        }

        $this->logger->info('Copy file: {source} -> {target}', [
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

    public function delete(string $path)
    {
        if (is_file($path) || is_link($path))
        {
            if (!unlink($dir))
            {
                $this->logger->error('Delete: {path} unlink error', [
                    'path' => $path
                ]);

                return false;
            }

            return true;
        }

        if (!is_dir($dir))
        {
            $this->logger->error('Delete: {path} not found', [
                'path' => $path
            ]);

            return false;
        }

        $hidden = true;

        $return = true;

        foreach(directory_map($path, 1, $hidden) as $file)
        {
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
            $this->logger->error('Delete: {path} rmdir error', [
                'path' => $path
            ]);

            return false;
        }

        $this->logger->info('Delete: {path}', [
            'path' => $path
        ]);

        return true;
    }

}