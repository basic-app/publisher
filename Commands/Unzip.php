<?php
/**
 * @author Basic App Dev Team
 * @license MIT
 * @link https://basic-app.com
 */
namespace BasicApp\Publisher\Commands;

use BasicApp\Publisher\Operations\UnzipOperation;

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
    protected $usage = 'ba:unzip {source} {target} {entries}';

    public function run(array $params)
    {
        list($source, $target, $entries) = $params;

        $entries = exlode(',', $entries);

        new UnzipOperation($source, $target, $entries)
            ->setLogger(new PublsiherLogger)
            ->run();
    }

}