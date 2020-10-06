<?php
/**
 * @author Basic App Dev Team
 * @license MIT
 * @link https://basic-app.com
 */
namespace BasicApp\Publisher;

use BasicApp\Publisher\Events\PublishEvent;
use BasicApp\Publisher\Events\BeforePublishEvent;
use BasicApp\Publisher\Events\AfterPublishEvent;
use BasicApp\ConsoleLogger\ConsoleLogger;

class PublisherEvents extends \CodeIgniter\Events\Events
{

    const EVENT_PUBLISH = 'ba:publish';
    
    const EVENT_BEFORE_PUBLISH = 'ba:before_publish';
    
    const EVENT_AFTER_PUBLISH = 'ba:after_publish';

    public static function publish(bool $refresh = false)
    {
        $event = new PublishEvent;

        $event->refresh = $refresh;

        $event->publisher = service('publisher');

        $event->publisher->setLogger(new ConsoleLogger);

        static::trigger(static::EVENT_PUBLISH, $event);

        return $event;
    }

    public static function beforePublish(bool $refresh = false)
    {
        $event = new BeforePublishEvent;

        $event->refresh = $refresh;

        $event->publisher = service('publisher');

        $event->publisher->setLogger(new ConsoleLogger);

        static::trigger(static::EVENT_BEFORE_PUBLISH, $event);

        return $event;
    }

    public static function afterPublish(bool $refresh = false)
    {
        $event = new AfterPublishEvent;

        $event->refresh = $refresh;

        $event->publisher = service('publisher');

        $event->publisher->setLogger(new ConsoleLogger);

        static::trigger(static::EVENT_AFTER_PUBLISH, $event);

        return $event;
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