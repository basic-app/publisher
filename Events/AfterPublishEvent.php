<?php
/**
 * @author Basic App Dev Team
 * @license MIT
 * @link https://basic-app.com
 */
namespace BasicApp\Publisher\Events;

class AfterPublishEvent extends \BasicApp\Event\BaseEvent
{

    public $refresh = false;

    public $publisher;

}