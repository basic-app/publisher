<?php
/**
 * @author Basic App Dev Team
 * @license MIT
 * @link https://basic-app.com
 */
namespace BasicApp\Publisher\Operations;

use Psr\Log\LoggerInterface;

class CopyOperation extends \BasicApp\Publisher\BaseOperation
{

    public $source;

    public $target;

    public $overwrite = true;

    public $hidden = true;

    public function __construct(string $source, string $target, bool $overwrite = true, bool $hidden = true)
    {
        parent::__construct();

        $this->source = $source;

        $this->target = $target;

        $this->overwrite = $overwrite;

        $this->hidden = $hidden;
    }

    public function run()
    {
        clearstatcache();

        if (!$overwrite && $this->isExists($this->target))
        {
            $this->logger->debug('Copy {source} to {target}. Target is exists.', [
                'source' => $this->source,
                'target' => $this->target,
                'overwrite' => $this->overwrite
            ]);            

            return;
        }

        if (is_link($this->source))
        {
            $this->copyLink($this->source, $this->target);
        }
        elseif(is_dir($this->source))
        {
            $this->copyDirectory($this->source, $this->target, $this->hidden);
        }
        elseif(is_file($this->source))
        {
            $this->copyFile($this->source, $this->target);
        }

        $this->logger->error('{source} not found.', [
            'source' => $this->source,
            'target' => $this->target,
            'overwrite' => $this->overwrite
        ]);
    }

    public function createDirectory(string $target)
    {
        $return = (new CreateDirectoryOperation($target))->run();

        if (!$return)
        {
            $this->logger->error('Directory {target} not created.', [
                'target' => $target
            ]);
        }

        return $return;
    }

    public function getDirectoryName(string $path) : string
    {
        $targetDirectory = pathinfo($path, PATHINFO_DIRNAME);

        if (!$targetDirectory)
        {
            $this->logger->error('Can\'t get directory name from {path}.', [
                'path' => $path
            ]);
        
            return false;
        }

        return $targetDirectory;
    }

    public function copyLink(string $source, string $target)
    {
        $link = readlink($source);

        if ($link === false)
        {
            $this->logger->error('Read link {source} error.', [
                'source' => $source,
                'target' => $target
            ]);

            return false;
        }

        if (!symlink($link, $target))
        {
            $this->logger->error('Create symlink from {source} to {target} error.', [
                'source' => $source,
                'target' => $target
            ]);

            return false;
        }

        $this->logger->info('Symlink to {target} from {source} created.', [
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
            $this->logger->error('Copying file {source} to {target} error.', [
                'source' => $source,
                'target' => $target
            ]);

            return false;
        }

        $this->logger->info('File {source} was copied to {target}.', [
            'source' => $source,
            'target' => $target
        ]);

        return true;
    }

    public function copyDirectory()
    {

        helper('filesystem');





        return true;
    }    



    /*



    public static function copyFile($source, $dest, $permission = 0755, $throwExceptions = true, &$error = null)
    {


        if (!static::createDirectory($dir, $permission, true, $throwExceptions, $error))
        {
            return static::_returnFalse($error, $throwExceptions);
        }


        return true;
    }

    public static function copyDirectory($source, $dest, $permission = 0755, $throwExceptions, &$error = null)
    {
        if (!is_dir($dest))
        {        
            if (!static::createDirectory($dest, $permission, true, $throwExceptions, $error))
            {
                return static::_returnFalse($error, $throwExceptions);
            }
        }

        $items = static::readDirectory($source, $throwExceptions, $error);

        if ($items === false)
        {
            return static::_returnFalse($error, $throwExceptions);
        }

        foreach($items as $file)
        {
            if (!static::copy($source . DIRECTORY_SEPARATOR . $file, $dest . DIRECTORY_SEPARATOR . $file, $permission, $throwExceptions, $error))
            {
                return static::_returnFalse($error, $throwExceptions);
            }
        }

        return true;
    }
    */

    /*

    public static function readDirectory($source, $throwExceptions = true, &$error = null)
    {
        $dir = dir($source);

        if (!$dir)
        {
            $error = 'Can\'t open directory: ' . $dir;

            return static::_returnFalse($error, $throwExceptions);
        }

        $items = [];
        
        while(false !== ($file = $dir->read()))
        {
            if ($file == '.' || $file == '..')
            {
                continue;
            }

            $items[] = $file;
        }

        $dir->close();

        return $items;
    }

    */

}