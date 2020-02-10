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

use GuzzleHttp\Client;
use Webjump\DrupalStock\Api\BoostInterface;
use Webjump\DrupalStock\Model\Logger\Logger;
use Webjump\DrupalStock\Model\Config;

class Boost implements BoostInterface
{
    public function __construct(Logger $logger, Config $config)
    {
        $this->logger = $logger;
        $this->config = $config;
    }

    /**
     * @var Logger
     */
    private $logger;

    /**
     * {@inheritdoc}
     */
    public function sendPost(array $message)
    {
        $requeest  = new Client();
        $response = $requeest->request(
            'POST', $this->config->getScopeConfigApiUri(),
            [
                'headers' => [
                    'Content-Type' => 'application/x-www-form-urlencoded',
                    'cache-control' => 'no-cache'
                ],
                'body' => [
                    'api_key' => $this->config->getScopeConfigApiKey(),
                    'sku' => $message['sku'],
                    'stock' => $message['stock'],
                    'price' => $message['price']
                ]
            ]
        );

        $this->logger->debug("My favorite test: " . $response->getBody()->getContents());
    }
}