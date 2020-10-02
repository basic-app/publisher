<?php
/**
 * @author Basic App Dev Team
 * @license MIT
 * @link https://basic-app.com
 */
namespace BasicApp\Publisher;

use BasicApp\Publisher\Events\PublishEvent;
use Psr\Log\LoggerInterface;

class PublisherEvents extends \CodeIgniter\Events\Events
{

    const EVENT_PUBLISH = 'ba:publish';
    
    const EVENT_BEFORE_PUBLISH = 'ba:before_publish';
    
    const EVENT_AFTER_PUBLISH = 'ba:after_publish';

    public static function publish(bool $refresh = false, ?LoggerInterface $logger)
    {
        $event = new PublishEvent;

        if ($logger)
        {
            $event->setLogger($logger);
        }

        $event->refresh = $refresh;

        static::trigger(static::EVENT_PUBLISH, $event);

        return $event;
    }

    public static function beforePublish(PublishEvent $event)
    {
        static::trigger(static::EVENT_BEFORE_PUBLISH, $event);
    }

    public static function afterPublish(PublishEvent $event)
    {
        static::trigger(static::EVENT_AFTER_PUBLISH, $event);
    }

    public static function onPublish($callback)
    {
        static::on(static::EVENT_PUBLISH, $callback);
    }

    public static function onBeforePublish($callback)
    {
        static::on(static::EVENT_BEFORE_PUBLISH, $callback);
    }

    public static function onAfterPublish($callback)
    {
        static::on(static::EVENT_AFTER_PUBLISH, $callback);
    }    

}