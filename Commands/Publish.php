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
    protected $description = 'Publish vendor assets.';

    /**
     * the Command's usage
     *
     * @var string
     */
    protected $usage = 'ba:publish';

    public function run(array $params)
    {
        $refresh = false;

        $event = PublisherEvents::publish($refresh);

        PublisherEvents::beforePublish($event);

        foreach($event->getOperations() as $operation)
        {
            $operation->run();
        }

        PublisherEvents::afterPublish($event);
    }

}