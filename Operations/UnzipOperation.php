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
            $this->throwException('{zipFile} is not found.', [
                'zipFile' => $this->zipFile
            ]);
        }

        $zip = new ZipArchive;
        
        if ($zip->open($this->zipFile) !== true)
        {
            $this->throwException('{zipFile} open error.', [
                'zipFile' => $this->zipFile
            ]);
        }

        if (!$zip->extractTo($this->targetDirectory, $this->entries))
        {
            $this->throwException('{zipFile} extract to {targetDirectory} error.', [
                'zipFile' => $this->zipFile,
                'targetDirectory' => $this->targetDirectory
            ]);
        }
        
        if (!$zip->close())
        {
            $this->throwException('{zipFile} close error.', [
                'zipFile' => $this->zipFile
            ]);
        }

        $logger->info('{zipFile} is extracted to {targetDirectory}.', [
            'zipFile' => $this->zipFile,
            'targetDirectory' => $this->targetDirectory,
            'entries' => $this->entries
        ]);
    }

}