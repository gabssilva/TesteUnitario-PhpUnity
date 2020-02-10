<?php
/**
 * @author      Webjump Core Team <dev@webjump.com.br>
 * @copyright   2018 Webjump (http://www.webjump.com.br)
 * @license     http://www.webjump.com.br  Copyright
 *
 * @link        http://www.webjump.com.br
 */
declare(strict_types=1);

namespace Webjump\DrupalStock\Model;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;
use Webjump\DrupalStock\Api\ConfigInterface;

class Config implements ConfigInterface
{
    const XML_PATH_BOOST_STOCK_API_ENABLE = 'boost_stock_api/general/enable';
    const XML_PATH_BOOST_STOCK_API_KEY = 'boost_stock_api/boost/api_key';
    const XML_PATH_BOOST_STOCK_API_URI = 'boost_stock_api/boost/endpoint';
    const XML_PATH_RABBIT_RETRIES = 'boost_stock_api/rabbit/retries';

    /**
     * @var ScopeConfigInterface
    */
    private $scopeConfig;

    /**
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig
    ) {
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * {@inheritDoc}
     */
    public function getConfigValue($path)
    {
        return $this->scopeConfig->getValue($path,ScopeInterface::SCOPE_WEBSITE);
    }

    /**
     * {@inheritDoc}
     */
    public function getScopeConfigRetries(): int
    {
        return (int)$this->scopeConfig->getValue(self::XML_PATH_RABBIT_RETRIES, ScopeInterface::SCOPE_WEBSITE);
    }

    /**
     * {@inheritDoc}
     */
    public function isBoostStockApiEnabled(): bool
    {
        return (bool)$this->scopeConfig->getValue(self::XML_PATH_BOOST_STOCK_API_ENABLE, ScopeInterface::SCOPE_STORES);
    }

    /**
     * {@inheritDoc}
     */
    public function getScopeConfigApiKey(): string
    {
        return (string)$this->scopeConfig->getValue(self::XML_PATH_BOOST_STOCK_API_KEY, ScopeInterface::SCOPE_STORES);
    }

    /**
     * {@inheritDoc}
     */
    public function getScopeConfigApiUri(): string
    {
        return (string)$this->scopeConfig->getValue(self::XML_PATH_BOOST_STOCK_API_URI, ScopeInterface::SCOPE_STORES);
    }
}