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

    public $curlOptions = [];

    public function __construct(string $url, string $target, array $curlOptions = [])
    {
        parent::__construct();

        $this->url = $url;

        $this->target = $target;

        $this->curlOptions = $curlOptions;
    }

    public function run()
    {
        if ($this->pathIsExists($this->target) && !$overwrite)
        {
            $this->logger->debug('{target} is exists.', [
                'target' => $this->target
            ]);

            return;
        }

        service('curl')->download($this->url, $this->target, $this->curlOptions);

        $this->logger->info('{url} is downloaded to {target}.', [
            'url' => $this->url,
            'target' => $this->target,
            'curlOptions' => $this->curlOptions
        ]);
    }

}