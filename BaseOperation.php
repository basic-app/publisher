<?php
/**
 * @author Basic App Dev Team
 * @license MIT
 * @link https://basic-app.com
 */
namespace BasicApp\Publisher;

use BasicApp\Publisher\PublisherException;

abstract class BaseOperation implements OperationInterface
{

    abstract public function run();

    public function throwException(string $message, array $context = [])
    {
        $replace = [];

        foreach($context as $key => $value)
        {
            $replace['{' . $key . '}'] = $value;
        }

        throw new PublisherException(strtr($message, $replace));
    }

}