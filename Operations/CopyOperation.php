<?php
/**
 * @author Basic App Dev Team
 * @license MIT
 * @link https://basic-app.com
 */
namespace BasicApp\Publisher\Operations;

class CopyOperation extends \BasicApp\Publisher\BaseOperation
{

    public $sourcePath;

    public $targetPath;

    public $overwrite = true;

    public function __construct(string $path, array $only = [], array $except = [])
    {
        parent::__construct();

        $this->path = $path;

        $this->only = $only;

        $this->except = $except;
    }

    public function run(LoggerInterface $logger)
    {
        helper('filesystem');
        
        /*
        clearstatcache();

        if (!is_dir($this->directory))
        {
            $this->throwException('Directory {directory} is not found.', [
                'directory' => $this->directory
            ]);
        }

        // delete directory

        */

        $logger->info('Files in {path} deleted.', [
            'path' => $this->path,
            'except' => $this->except,
            'only' => $this->only
        ]);
    }

}