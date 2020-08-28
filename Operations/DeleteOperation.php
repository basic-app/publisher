<?php
/**
 * @author Basic App Dev Team
 * @license MIT
 * @link https://basic-app.com
 */
namespace BasicApp\Publisher\Operations;

use Psr\Log\LoggerInterface;

class DeleteDirectoryOperation extends \BasicApp\Publisher\BaseOperation
{

    public $directory;

    public $keepRootDirectory = false;

    public function __construct(string $directory, bool $keepRootDirectory = false)
    {
        parent::__construct();

        $this->directory = $directory;

        $this->keepRootDirectory = $keepRootDirectory;
    }

    public function run(LoggerInterface $logger)
    {
        clearstatcache();

        if (!is_dir($this->directory))
        {
            $this->logger->error('Directory {directory} is not found.', [
                'directory' => $this->directory
            ]);

            return;
        }

        // delete directory

        $this->logger->info('{directory} is deleted.', [
            'directory' => $this->directory,
            'keepRootDirectory' => $this->keepRootDirectory
        ]);
    }

}