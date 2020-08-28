<?php
/**
 * @author Basic App Dev Team
 * @license MIT
 * @link https://basic-app.com
 */
use BasicApp\Publisher\Operations\CopyOperation;
use BasicApp\Publisher\Operations\DeleteOperation;
use BasicApp\Publisher\Operations\SetPermissionsOperation;
use BasicApp\Publisher\Operations\DownloadOperation;
use BasicApp\Publisher\Operations\ZipOperation;
use BasicApp\Publisher\HelperLogger;

if (!function_exists('ba_copy'))
{
    function ba_copy(string $source, string $target, bool $overwrite = true)
    { 
        return new CopyOperation($source, $target, $overwrite)
            ->setLogger(new HelperLogger)
            ->run();
    }
}

if (!function_exists('ba_delete'))
{
    function ba_delete(string $path)
    { 
        return new DeleteOperation($path)
            ->setLogger(new HelperLogger)
            ->run();
    }
}

if (!function_exists('ba_set_permissions'))
{
    function ba_set_permissions(string $path, string $permissions)
    { 
        return new SetPermissionsOperation($path, $permissions)
            ->setLogger(new HelperLogger)
            ->run();
    }
}

if (!function_exists('ba_download'))
{
    function ba_download(string $url, string $target, bool $overwrite = true)
    { 
        return new DownloadOperation($url, $target, $overwrite)
            ->setLogger(new HelperLogger)
            ->run();
    }
}

if (!function_exists('ba_unzip'))
{
    function ba_unzip(string $file, string $target, array $entries = [])
    { 
        return new UnzipOperation($file, $target, $entries)
            ->setLogger(new HelperLogger)
            ->run();
    }
}

if (!function_exists('ba_create_directory'))
{
    function ba_create_directory(string $path, $permissions, bool $recursive = [])
    { 
        return new UnzipOperation($path, $permissions, $recursive)
            ->setLogger(new HelperLogger)
            ->run();
    }
}