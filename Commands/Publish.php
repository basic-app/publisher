<?php

namespace BasicApp\Publisher\Commands;

use BasicApp\Publisher\PublisherEvents;
use BasicApp\Publisher\Events\PublishEvent;

class Publish extends \BasicApp\Command\BaseCommand
{

    /**
     * The group the command is lumped under
     * when listing commands.
     *
     * @var string
     */
    protected $group = 'Basic App';

    /**
     * The Command's name
     *
     * @var string
     */
    protected $name = 'ba:publish';

    /**
     * the Command's short description
     *
     * @var string
     */
    protected $description = 'Publish assets.';

    /**
     * the Command's usage
     *
     * @var string
     */
    protected $usage = 'ba:publish';

    public function run(array $params)
    {
        $config = PublisherEvents::publish();

        foreach($config[PublishEvent::COPY_FILE] as $row)
        {
            $this->copyFile($row);
        }

        foreach($config[PublishEvent::COPY_DIRECTORY] as $row)
        {
            $this->copyDirectory($row);
        }

        foreach($config[PublishEvent::FILE_PERMISSIONS] as $row)
        {
            $this->filePermissions($row);
        }

        foreach($config[PublishEvent::DIRECTORY_PERMISSIONS] as $row)
        {
            $this->directoryPermissions($row);
        }
    }

    public function copyFile(array $config = [])
    {
        extract($config);
    }

    public function copyDirectory(array $config = [])
    {
        extract($config);
    }

    public function filePermissions(array $config = [])
    {
        extract($config);
    }

    public function directoryPermissions(array $config = [])
    {
        extract($config);
    }

}