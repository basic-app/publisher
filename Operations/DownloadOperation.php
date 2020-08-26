<?php
/**
 * @author Basic App Dev Team
 * @license MIT
 * @link https://basic-app.com
 */
namespace BasicApp\Publisher\Operations;

use ZipArchive;
use Psr\Log\LoggerInterface;

class OperationOperation extends \BasicApp\Publisher\BaseOperation
{

    public $sourceUrl;

    public $targetFile;

    public $curlOptions = [];

    public function __construct(string $sourceUrl, string $targetFile, array $curlOptions = [])
    {
        parent::__construct();

        $this->sourceUrl = $sourceUrl;

        $this->targetFile = $targetFile;

        $this->curlOptions = $curlOptions;
    }

    public function run(LoggerInterface $logger)
    {
        if (is_file($target) && !$overwrite)
        {
            $logger->debug($this->targetFile . ' is exists.');

            return;
        }

        service('curl')->download($this->sourceUrl, $this->targetFile, $this->curlOptions);

        $logger->info('{sourceFile} is downloaded to {targetFile}.', [
            'sourceUrl' => $this->sourceUrl,
            'targetFile' => $this->targetFile,
            'curlOptions' => $this->curlOptions
        ]);
    }

}