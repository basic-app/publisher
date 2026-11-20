<?php

declare(strict_types=1);
/**
 * @author Basic App Dev Team
 * @license MIT
 */

namespace BasicApp\Publisher;

use CodeIgniter\Publisher\Publisher as BasePublisher;
use Config\Publisher as PublisherConfig;
use Exception;
use ZipArchive;

class Publisher extends BasePublisher
{
    /**
     * Tell Publisher where to create destination directory.
     */
    protected bool $createDestination = false;

    /**
     * Custom restrictions.
     */
    protected ?string $customRestrictions = null;

    /**
     * Loads the helper and verifies the source and destination directories.
     */
    public function __construct(?string $source = null, ?string $destination = null)
    {
        if ($this->createDestination) {
            $this->createDir($this->destination);
        }

        if ($this->customRestrictions) {
            $config = config(PublisherConfig::class);

            $savedRestrictions = $config->restrictions[FCPATH];

            $config->restrictions[FCPATH] = $this->customRestrictions;

            parent::__construct($source, $destination);

            if ($this->customRestrictions) {
                $config->restrictions[FCPATH] = $savedRestrictions;
            }
        } else {
            parent::__construct($source, $destination);
        }
    }

    /**
     * Create directory.
     */
    public function createDir(string $dir)
    {
        if (!is_dir($dir)) {
            mkdir($dir, 0775, true);
        }

        return $this;
    }

    public function isEmpty(string $dir): bool
    {
        helper(['filesystem']);

        return directory_map($dir, 0, true) ? false : true;
    }

    public function setSource(string $path)
    {
        $this->source = static::resolveDirectory($path);

        return $this;
    }

    public function extractZip(string $filename, $files = null, $targetPath = null)
    {
        if (!$targetPath) {
            $targetPath = $this->getScratch();
        }

        $filename = self::resolveFile($this->getScratch() . $filename);

        if (!is_file($filename)) {
            throw new Exception('File not found: ' . $filename);
        }

        $zip = new ZipArchive();

        $res = $zip->open($filename);

        if ($res !== true) {
            throw new Exception('Can\'t open file: ' . $filename);
        }

        if ($zip->extractTo($targetPath, $files) === false) {
            throw new Exception('Extracting zip archive failed: ' . $filename);
        }

        if ($zip->close() === false) {
            throw new Exception('Closing zip archive failed: ' . $filename);
        }

        return $this;
    }

    public function removeScratchFile(string $filename)
    {
        $filename = self::resolveFile($this->getScratch() . $filename);

        $this->removeFile($filename);

        return $this;
    }
}
