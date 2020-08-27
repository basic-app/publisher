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

    public function __construct(LoggerInterface $logger, string $zipFile, string $targetDirectory, array $entries = [])
    {
        parent::__construct();

        $this->zipFile = $zipFile;

        $this->targetDirectory = $targetDirectory;

        $this->entries = $entries;
    }

    public function run()
    {
        if (!is_file($this->zipFile))
        {
            $this->error('{zipFile} is not found.', [
                'zipFile' => $this->zipFile
            ]);

            return;
        }

        $zip = new ZipArchive;
        
        if ($zip->open($this->zipFile) !== true)
        {
            $this->error('{zipFile} open error.', [
                'zipFile' => $this->zipFile
            ]);

            return;
        }

        if (!$zip->extractTo($this->targetDirectory, $this->entries))
        {
            $this->error('{zipFile} extract to {targetDirectory} error.', [
                'zipFile' => $this->zipFile,
                'targetDirectory' => $this->targetDirectory
            ]);

            return;
        }
        
        if (!$zip->close())
        {
            $this->error('{zipFile} close error.', [
                'zipFile' => $this->zipFile
            ]);

            return;
        }

        $this->info('{zipFile} is extracted to {targetDirectory}.', [
            'zipFile' => $this->zipFile,
            'targetDirectory' => $this->targetDirectory,
            'entries' => $this->entries
        ]);
    }

}