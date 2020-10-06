<?php
/**
 * @author Basic App Dev Team <dev@basic-app.com>
 * @license MIT
 * @link https://basic-app.com
 */
namespace BasicApp\Publisher\Config;

use BasicApp\Publisher\PublisherService;

class Services extends \CodeIgniter\Config\BaseService
{

    public static function publisher($getShared = true)
    {
        if (!$getShared)
        {
            return new PublisherService;
        }

        return static::getSharedInstance(__FUNCTION__);
    }

}