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

    public function run() : bool
    {
        clearstatcache();

        if (!$this->overwrite && $this->isExists($this->target))
        {
            $this->logger->debug('{source} -> {target} exists', [
                'source' => $this->source,
                'target' => $this->target,
                'overwrite' => $this->overwrite
            ]);            

            return true;
        }

        if (is_link($this->source))
        {
            $this->copyLink($this->source, $this->target);
        
            return true;
        }
        elseif(is_dir($this->source))
        {
            $this->copyDirectory($this->source, $this->target, $this->overwrite, $this->hidden);
        
            return true;
        }
        elseif(is_file($this->source))
        {
            $this->copyFile($this->source, $this->target);
        
            return true;
        }

        $this->logger->error('{source} -> {target } source error', [
            'source' => $this->source,
            'target' => $this->target,
            'overwrite' => $this->overwrite
        ]);

        return false;
    }

    public function createDirectory(string $target)
    {
        return (new CreateDirectoryOperation($target))
            ->setLogger($this->logger)
            ->run();
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

            (new CopyOperation($from, $to, $overwrite, $hidden))
                ->setLogger($this->logger)
                ->run();
        }

        return true;
    }

}