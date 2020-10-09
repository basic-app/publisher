<?php
/**
 * @author Basic App Dev Team
 * @license MIT
 * @link https://basic-app.com
 */
namespace BasicApp\Publisher\Commands;

use BasicApp\Publisher\PublisherEvents;

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

    protected $options = [
        '-refresh' => 'Refresh assets'
    ];

    public function run(array $params)
    {
        $options = $this->request->getOptions();

        if (array_key_exists('refresh', $options))
        {
            $refresh = true;
        }
        else
        {
            $refresh = false;
        }
        
        PublisherEvents::beforePublish($refresh);

        PublisherEvents::publish($refresh);

        PublisherEvents::afterPublish($refresh);
    }

}