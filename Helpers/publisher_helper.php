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

if (!function_exists('publisher_copy'))
{
    function publisher_copy(string $source, string $target, bool $overwrite = true)
    { 
        return new CopyOperation($source, $target, $overwrite)
            ->setLogger(new HelperLogger)
            ->run();
    }
}

if (!function_exists('publisher_delete'))
{
    function publisher_delete(string $path)
    { 
        return new DeleteOperation($path)
            ->setLogger(new HelperLogger)
            ->run();
    }
}

if (!function_exists('publisher_set_permissions'))
{
    function publisher_set_permissions(string $path, string $permissions)
    { 
        return new SetPermissionsOperation($path, $permissions)
            ->setLogger(new HelperLogger)
            ->run();
    }
}

if (!function_exists('publisher_download'))
{
    function publisher_download(string $url, string $target, bool $overwrite = true)
    { 
        return new DownloadOperation($url, $target, $overwrite)
            ->setLogger(new HelperLogger)
            ->run();
    }
}

if (!function_exists('publisher_unzip'))
{
    function publisher_unzip(string $file, string $target, array $entries = [])
    { 
        return new UnzipOperation($file, $target, $entries)
            ->setLogger(new HelperLogger)
            ->run();
    }
}