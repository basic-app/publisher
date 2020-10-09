<?php
/**
 * @author Basic App Dev Team <dev@basic-app.com>
 * @license MIT
 * @link https://basic-app.com
 */
namespace BasicApp\Publisher\Config;

use BasicApp\Publisher\PublisherService;
use BasicApp\ConsoleLogger\ConsoleLogger;

class Services extends \CodeIgniter\Config\BaseService
{

    public static function publisher($getShared = true)
    {
        if (!$getShared)
        {
            $return = new PublisherService;

            if (is_cli())
            {
                $return->setLogger(new ConsoleLogger);
            }

            return $return;
        }

        return static::getSharedInstance(__FUNCTION__);
    }

}