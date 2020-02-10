<?php
/**
 * @author      Webjump Core Team <dev@webjump.com.br>
 * @copyright   2018 Webjump (http://www.webjump.com.br)
 * @license     http://www.webjump.com.br  Copyright
 *
 * @link        http://www.webjump.com.br
 */
declare(strict_types=1);

namespace Webjump\DrupalStock\Handler;

use Webjump\DrupalStock\Model\Logger\Logger;
use Webjump\DrupalStock\Api\BoostInterface;
use Webjump\DrupalStock\Api\ConfigInterface;
use Webjump\DrupalStock\Api\ConsumerInterface;
use Webjump\DrupalStock\Api\QueueInterface;
use Webjump\DrupalStock\Model\Message;
use Magento\Framework\Serialize\SerializerInterface;

class Consumer implements ConsumerInterface
{
    /**
     * @var Logger
     */
    private $logger;

    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * @var QueueInterface
    */
    private $queue;

    /**
     * @var ConfigInterface
    */
    private $config;

    /**
     * @var BoostInterface
    */
    private $boost;

    /**
     * @param Logger $logger
     * @param SerializerInterface $serializer
     * @param QueueInterface $queue
     * @param ConfigInterface $config
     * @param BoostInterface $boost
     */
    public function __construct(
        Logger $logger,
        SerializerInterface $serializer,
        QueueInterface $queue,
        ConfigInterface $config,
        BoostInterface $boost
    ) {
        $this->logger = $logger;
        $this->serializer = $serializer;
        $this->queue = $queue;
        $this->config = $config;
        $this->boost = $boost;
    }

    /**
     * {@inheritdoc}
     */
    public function process(Message $message)
    {
        $response = $this->queue->messageToArray($message);
        try {
            if ($this->validator($response)) {
                $this->logger->debug('Message received: ' . $message->getMessage());
                $this->boost->sendPost($response);
            }
        } catch (\Exception $e) {
            $this->logger->error('Message error: Cannot process:' . $e->getMessage());
            $this->retryMessage($message, $response);
        }
        return $this;
    }

    /**
     * @param array $response
     * @return bool
     */
    private function validator($response): bool
    {
        if (empty($response['sku'])) {
            $this->logger->error('Message Error: No return');
            return false;
        }
        if ((int)$response['retries'] >= $this->config->getScopeConfigRetries()) {
            $this->logger->error(sprintf('Message Error: SKU: %s - Number of retries exceeded', $response['sku']));
            return false;
        }
        return true;
    }

    /**
     * @param Message $message
     * @param array $response
    */
    private function retryMessage(Message $message, array $response)
    {
        $response['retries']++;
        $message->setMessage($this->serializer->serialize($response));
        $this->queue->send($message);
    }
}