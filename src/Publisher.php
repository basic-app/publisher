<?php
/**
 * @author Basic App Dev Team
 * @license MIT
 */
namespace BasicApp\Publisher;

use CodeIgniter\Publisher\Publisher as BasePublisher;
use Config\Publisher as PublisherConfig;

class Publisher extends BasePublisher
{
    /**
     * Tell Publisher where to create destination directory.
     */
    protected $createDestination = false;

    /**
     * Custom restrictions.
     */
    protected $customRestrictions;

    /**
     * Loads the helper and verifies the source and destination directories.
     */
    public function __construct(?string $source = null, ?string $destination = null)
    {
        if ($this->createDestination)
        {
            $this->createDirectory($this->destination);
        }

        if ($this->customRestrictions)
        {
            $config = config(PublisherConfig::class);

            $restrictions = $config->restrictions[FCPATH];

            $config->restrictions[FCPATH] = $this->customRestrictions;
        }

        parent::__construct($source, $destination);
    
        if ($this->customRestrictions)
        {
            $config->restrictions[FCPATH] = $restrictions;
        }
    }

    /**
     * Create directory.
     */
    public function createDirectory(string $dir)
    {
        if (!is_dir($dir))
        {
            mkdir($dir, 0775, true);
        }
    }

    /**
     * Delete files.
     */
    public function deleteFiles(string $dir)
    {
        helper(['filesystem']);

        delete_files($dir, true, false, true);
        
        return true;
    }

    public function directoryIsEmpty(string $dir) : bool
    {
        helper(['filesystem']);

        return directory_map($dir, 0, true) ? false : true;
    }
}