<?php
/**
 * @author Basic App Dev Team
 * @license MIT
 * @link https://basic-app.com
 */
namespace BasicApp\Publisher\Commands;

use BasicApp\Publisher\Operations\CopyOperation;

class Copy extends \BasicApp\Command\BaseCommand
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
    protected $name = 'ba:copy';

    /**
     * the Command's short description
     *
     * @var string
     */
    protected $description = 'Copy directories and files.';

    /**
     * the Command's usage
     *
     * @var string
     */
    protected $usage = 'ba:copy {source} {target} {overwrite}';

    public function run(array $params)
    {
        list($source, $target, $overwrite) = $params;

        new CopyOperation($source, $target, (bool) $overwrite)
            ->setLogger(new PublsiherLogger)
            ->run();
    }

}