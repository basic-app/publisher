<?php
/**
 * @author Basic App Dev Team
 * @license MIT
 * @link https://basic-app.com
 */
namespace BasicApp\Publisher\Commands;

use BasicApp\Publisher\Operations\UnzipOperation;
use BasicApp\ConsoleLogger\ConsoleLogger;

class Unzip extends \BasicApp\Command\BaseCommand
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
    protected $name = 'ba:unzip';

    /**
     * the Command's short description
     *
     * @var string
     */
    protected $description = 'Unzip directories and files.';

    /**
     * the Command's usage
     *
     * @var string
     */
    protected $usage = 'ba:unzip [source] [target] [entries]';

    public function run(array $params)
    {
        $params[0] = $this->rootPath($params[0]);

        $params[1] = $this->rootPath($params[1]);

        if (array_key_exists(2, $params))
        {
            $params[3] = exlode(',', $entries);
        }

        (new UnzipOperation(...$params))
            ->setLogger(new ConsoleLogger)
            ->run();
    }

}