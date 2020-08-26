<?php
/**
 * @author Basic App Dev Team
 * @license MIT
 * @link https://basic-app.com
 */
namespace BasicApp\Publisher;

use Events\PublishEvent;

class PublisherEvents
{

    const EVENT_PUBLISH = 'ba:publish';
    const EVENT_BEFORE_PUBLISH = 'ba:before_publish';
    const EVENT_AFTER_PUBLISH = 'ba:after_publish';

    public static function publish(array $config = [])
    {
        $event = new PublishEvent($config);

        static::trigger(static::EVENT_PUBLISH, $event);

        return $event->toArray();
    }

    public static function beforePublish(array $config = [])
    {
        list($config) = static::trigger(static::EVENT_BEFORE_PUBLISH, [$config]);

        return $config;
    }

    public static function publish(array $config = [])
    {
        $event = new PublishEvent($config);

        static::trigger(static::EVENT_PUBLISH, $event);

        return $event->toArray();
    }

    public static function afterPublish(array $config = [])
    {
        list($config) = static::trigger(static::EVENT_AFTER_PUBLISH, [$config]);

        return $config;
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