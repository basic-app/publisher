<?php
/**
 * @author Basic App Dev Team
 * @license MIT
 * @link https://basic-app.com
 */
namespace BasicApp\Publisher\Commands;

use BasicApp\Publisher\Operations\SetPermissionsOperation;

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
    protected $name = 'ba:setPermissions';

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
    protected $usage = 'ba:setPermissions {path} {permissions}';

    public function run(array $params)
    {
        list($path, $permissions) = $params;

        new SetPermissionsOperation($path, $permissions)
            ->setLogger(new PublsiherLogger)
            ->run();
    }

}