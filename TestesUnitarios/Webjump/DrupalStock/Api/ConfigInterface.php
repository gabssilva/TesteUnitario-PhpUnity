<?php
/**
 * @author      Webjump Core Team <dev@webjump.com.br>
 * @copyright   2018 Webjump (http://www.webjump.com.br)
 * @license     http://www.webjump.com.br  Copyright
 *
 * @link        http://www.webjump.com.br
 */

namespace Webjump\DrupalStock\Api;


interface ConfigInterface
{
    /**
     * @param string $field
     * @return mixed
     */
    public function getConfigValue($field);

    /**
     * @return int
    */
    public function getScopeConfigRetries();

    /**
     * @return bool
    */
    public function isBoostStockApiEnabled();
}