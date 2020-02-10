<?php
/**
 * @author      Webjump Core Team <dev@webjump.com.br>
 * @copyright   2018 Webjump (http://www.webjump.com.br)
 * @license     http://www.webjump.com.br  Copyright
 *
 * @link        http://www.webjump.com.br
 */
declare(strict_types=1);

namespace Webjump\DrupalStock\Api;


use Webjump\DrupalStock\Model\Message;

interface BoostInterface
{
    /**
     * @param array $message
     * @return void
     */
    public function sendPost(array $message);
}