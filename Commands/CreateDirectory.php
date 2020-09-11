<?php
/**
 * @author Basic App Dev Team
 * @license MIT
 * @link https://basic-app.com
 */
namespace BasicApp\Publisher\Commands;

use BasicApp\Publisher\Operations\CreateDirectoryOperation;
use BasicApp\ConsoleLogger\ConsoleLogger;

class CreateDirectory extends \BasicApp\Command\BaseCommand
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
    protected $name = 'ba:create-directory';

    /**
     * the Command's short description
     *
     * @var string
     */
    protected $description = 'Create directory.';

    /**
     * the Command's usage
     *
     * @var string
     */
    protected $usage = 'ba:create-directory [path] [permissions] [recursive]';

    public function run(array $params)
    {
        $params[0] = $this->rootPath($params[0]);

        (new CreateDirectoryOperation(...$params))
            ->setLogger(new ConsoleLogger)
            ->run();
    }

}