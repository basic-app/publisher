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

    public static function publish(array $config = [])
    {
        $event = new PublishEvent($config);

        static::trigger(static::EVENT_PUBLISH, $event);

        return $event->toArray();
    }

    public static function onPublish($callback)
    {
        static::on(static::EVENT_PUBLISH, $callback);
    }

}