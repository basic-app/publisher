<?php
/**
 * @author Basic App Dev Team
 * @license MIT
 * @link https://basic-app.com
 */
namespace BasicApp\Publisher\Operations;

class DeleteDirectoryOperation extends \BasicApp\Publisher\BaseOperation
{

    public $path;

    public $keepRootDirectory = false;

    public function __construct(string $path, bool $keepRootDirectory = false)
    {
        parent::__construct();

        $this->path = $path;

        $this->keepRootDirectory = $keepRootDirectory;
    }

    public function run()
    {

        
    }

}