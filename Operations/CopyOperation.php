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

    public function __construct(string $source, string $target, bool $overwrite = true)
    {
        parent::__construct();

        $this->source = $source;

        $this->target = $target;

        $this->overwrite = $overwrite;
    }

    public function run()
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

        /*
        $this->logger->info('Files in {path} deleted.', [
            'path' => $this->path,
            'except' => $this->except,
            'only' => $this->only
        ]);
        */
    }

}