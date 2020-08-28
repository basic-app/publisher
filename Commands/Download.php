<?php
/**
 * @author Basic App Dev Team
 * @license MIT
 * @link https://basic-app.com
 */
namespace BasicApp\Publisher\Commands;

use BasicApp\Publisher\Operations\DownloadOperation;

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
    protected $usage = 'ba:download {source} {target}';

    public function run(array $params)
    {
        list($source, $target) = $params;

        new DownloadOperation($source, $target, (bool) $overwrite)
            ->setLogger(new PublsiherLogger)
            ->run();
    }

}