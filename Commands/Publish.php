<?php
/**
 * @author Basic App Dev Team
 * @license MIT
 * @link https://basic-app.com
 */
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

        $config = PublisherEvents::beforePublish($config);

        foreach($config[PublishEvent::DELETE_DIRECTORY] as $row)
        {
            $this->deleteDirectory($row);
        }

        foreach($config[PublishEvent::DELETE_FILE] as $row)
        {
            $this->deleteFile($row);
        }        

        foreach($config[PublishEvent::DOWNLOAD] as $row)
        {
            $this->download($row);
        }

        foreach($config[PublishEvent::UNZIP] as $row)
        {
            $this->unzip($row);
        }

        foreach($config[PublishEvent::COPY_DIRECTORY] as $row)
        {
            $this->copyDirectory($row);
        }

        foreach($config[PublishEvent::COPY_FILE] as $row)
        {
            $this->copyFile($row);
        }

        foreach($config[PublishEvent::DIRECTORY_PERMISSIONS] as $row)
        {
            $this->directoryPermissions($row);
        }

        foreach($config[PublishEvent::FILE_PERMISSIONS] as $row)
        {
            $this->filePermissions($row);
        }

        PublisherEvents::afterPublish($config);
    }

    public function deleteFile(array $config = [])
    {
        extract($config);
    }

    public function deleteDirectory(array $config = [])
    {
        extract($config);
    }

    public function copyFile(array $config = [])
    {
        extract($config);
    }

    public function download(array $config = [])
    {
        extract($config);
    }

    public function unzip(array $config = [])
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