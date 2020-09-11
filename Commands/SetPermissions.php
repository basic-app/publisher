<?php
/**
 * @author Basic App Dev Team
 * @license MIT
 * @link https://basic-app.com
 */
namespace BasicApp\Publisher\Commands;

use BasicApp\Publisher\Operations\SetPermissionsOperation;
use BasicApp\ConsoleLogger\ConsoleLogger;

class SetPermissions extends \BasicApp\Command\BaseCommand
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
    protected $name = 'ba:set-permissions';

    /**
     * the Command's short description
     *
     * @var string
     */
    protected $description = 'Set permissions to directories and files.';

    /**
     * the Command's usage
     *
     * @var string
     */
    protected $usage = 'ba:set-permissions [path] [permissions]';

    public function run(array $params)
    {
        $params[0] = $this->rootPath($params[0]);

        (new SetPermissionsOperation(...$params))
            ->setLogger(new ConsoleLogger)
            ->run();
    }

}