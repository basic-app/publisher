<?php
/**
 * @author Basic App Dev Team
 * @license MIT
 * @link https://basic-app.com
 */
namespace BasicApp\Publisher\Operations;

use ZipArchive;
use Psr\Log\LoggerInterface;

class DownloadOperation extends \BasicApp\Publisher\BaseOperation
{

    public $url;

    public $target;

    public $overwrite;

    public $curlOptions = [];

    public function __construct(string $url, string $target, bool $overwrite = true, array $curlOptions = [])
    {
        parent::__construct();

        $this->url = $url;

        $this->target = $target;

        $this->overwrite = $overwrite;

        $this->curlOptions = $curlOptions;
    }

    public function run() : bool
    {
        if ($this->isExists($this->target) && !$this->overwrite)
        {
            $this->logger->debug('{url} -> {target} exists', [
                'url' => $this->url,
                'target' => $this->target
            ]);

            return true;
        }

        service('curl')->download($this->url, $this->target, $this->overwrite, $this->curlOptions);

        $this->logger->info('{url} -> {target}', [
            'url' => $this->url,
            'target' => $this->target,
            'curlOptions' => $this->curlOptions
        ]);

        return true;
    }

}