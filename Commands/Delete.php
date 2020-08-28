<?php
/**
 * @author Basic App Dev Team
 * @license MIT
 * @link https://basic-app.com
 */
namespace BasicApp\Publisher\Commands;

use BasicApp\Publisher\Operations\DeleteOperation;

class Delete extends \BasicApp\Command\BaseCommand
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
    protected $name = 'ba:delete';

    /**
     * the Command's short description
     *
     * @var string
     */
    protected $description = 'Delete directories and files.';

    /**
     * the Command's usage
     *
     * @var string
     */
    protected $usage = 'ba:delete {path}';

    public function run(array $params)
    {
        list($path) = $params;

        new DeleteOperation($path)
            ->setLogger(new PublsiherLogger)
            ->run();
    }

}