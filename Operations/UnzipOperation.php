<?php
/**
 * @author Basic App Dev Team
 * @license MIT
 * @link https://basic-app.com
 */
namespace BasicApp\Publisher\Operations;

use BasicApp\Publisher\PublisherException;
use ZipArchive;
use Psr\Log\LoggerInterface;

class UnzipOperation extends \BasicApp\Publisher\BaseOperation
{

    const MESSAGE = '{zipFile} is extracted to {targetDirectory}.';

    public $zipFile;

    public $targetDirectory;

    public $entries = [];

    public function __construct(string $zipFile, string $targetDirectory, array $entries = [])
    {
        parent::__construct();

        $this->zipFile = $zipFile;

        $this->targetDirectory = $targetDirectory;

        $this->entries = $entries;
    }

    public function run(LoggerInterface $logger)
    {
        if (!is_file($this->zipFile))
        {
            throw new PublisherException('File not found: ' . $this->zipFile);
        }

        $zip = new ZipArchive;
        
        if ($zip->open($this->zipFile) !== true)
        {
            throw new PublisherException('Can\'t open zip file: ' . $this->zipFile);
        }

        if (!$zip->extractTo($this->targetDirectory, $this->entries))
        {
            throw new PublisherException('Can\'t extract zip file "' . $this->zipFile . '" to "' . $this->targetDirectory . '".');
        }
        
        if (!$zip->close())
        {
            throw new PublisherException('Can\'t close zip file: ' . $this->zipFile);
        }

        $logger->info(static::MESSAGE, [
            'zipFile' => $this->zipFile,
            'targetDirectory' => $this->targetDirectory,
            'entries' => $this->entries
        ]);
    }

}