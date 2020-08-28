<?php
/**
 * @author Basic App Dev Team
 * @license MIT
 * @link https://basic-app.com
 */
namespace BasicApp\Publisher\Commands;

use BasicApp\Publisher\Operations\DownloadOperation;
use BasicApp\Publisher\PublisherLogger;

class Download extends \BasicApp\Command\BaseCommand
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
    protected $name = 'ba:download';

    /**
     * the Command's short description
     *
     * @var string
     */
    protected $description = 'Download files.';

    /**
     * the Command's usage
     *
     * @var string
     */
    protected $usage = 'ba:download [url] [path]';

    public function run(array $params)
    {
        $params[1] = $this->rootPath($params[1]);

        (new DownloadOperation(...$params))
            ->setLogger(new PublisherLogger)
            ->run();
    }

}