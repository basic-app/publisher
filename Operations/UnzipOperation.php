<?php
/**
 * @author Basic App Dev Team
 * @license MIT
 * @link https://basic-app.com
 */
namespace BasicApp\Publisher\Operations;

use ZipArchive;
use Psr\Log\LoggerInterface;

class UnzipOperation extends \BasicApp\Publisher\BaseOperation
{

    public $source;

    public $target;

    public $entries = [];

    public function __construct(string $source, string $target, array $entries = [])
    {
        parent::__construct();

        $this->source = $source;

        $this->target = $target;

        $this->entries = $entries;
    }

    public function run()
    {
        if (!is_file($this->source))
        {
            $this->logger->error('{source} not found.', [
                'source' => $this->source
            ]);

            return;
        }

        $zip = new ZipArchive;
        
        if ($zip->open($this->source) !== true)
        {
            $this->logger->error('{source} open error.', [
                'source' => $this->source
            ]);

            return;
        }

        if (!$zip->extractTo($this->target, (count($this->entries) > 0) ? $this->entries : null))
        {
            $this->logger->error('{source} extract to {target} error.', [
                'source' => $this->source,
                'target' => $this->target
            ]);

            return;
        }
        
        if (!$zip->close())
        {
            $this->logger->error('{source} close error.', [
                'source' => $this->source
            ]);

            return;
        }

        $this->logger->info('{source} is extracted to {target}.', [
            'source' => $this->source,
            'target' => $this->target,
            'entries' => $this->entries
        ]);
    }

}