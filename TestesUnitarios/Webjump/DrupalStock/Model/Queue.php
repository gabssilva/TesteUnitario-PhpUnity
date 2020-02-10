<?php
/**
 * @author      Webjump Core Team <dev@webjump.com.br>
 * @copyright   2018 Webjump (http://www.webjump.com.br)
 * @license     http://www.webjump.com.br  Copyright
 *
 * @link        http://www.webjump.com.br
 */

namespace Webjump\DrupalStock\Model;


use Magento\Framework\MessageQueue\PublisherInterface;
use Magento\Framework\Serialize\SerializerInterface;
use Webjump\DrupalStock\Api\QueueInterface;
use Webjump\DrupalStock\Model\Logger\Logger;
use Magento\Catalog\Model\Product\Type;
use Webjump\DrupalStock\Api\ConfigInterface;

class Queue implements QueueInterface
{
    const TOPIC = 'drupal.stock.topic';

    /**
     * @var PublisherInterface
     */
    private $publisher;

    /**
     * @var Logger
     */
    private $logger;

    /**
     * @var SerializerInterface
    */
    private $serializer;

    /**
     * @var ConfigInterface
     */
    private $config;

    /**
     * @param PublisherInterface $publisher
     * @param Logger $logger
     * @param SerializerInterface $serializer
     * @param ConfigInterface $config
     */
    public function __construct(
        PublisherInterface $publisher,
        Logger $logger,
        SerializerInterface $serializer,
        ConfigInterface $config
    ) {
        $this->publisher = $publisher;
        $this->logger = $logger;
        $this->serializer = $serializer;
        $this->config = $config;
    }

    /**
     * {@inheritdoc}
     */
    public function send($message)
    {
        $product = $this->messageToArray($message);
        if ($this->validate($product)) {
            $this->publisher->publish(self::TOPIC, $message);
            $this->logger->debug(sprintf('Published to topic "%s" the message "%s"', self::TOPIC, $message->getMessage()));
        }
    }

    /**
     * @param array $product
     * @return bool
     */
    private function validate($product): bool
    {
        if (!$this->config->isBoostStockApiEnabled()) {
            return false;
        }
        if ($product['type'] !== Type::TYPE_SIMPLE) {
            $this->logger->error('Published validate: product must be simple!');
            return false;
        }
        return true;
    }

    /**
     * @param Message $message
     * @return array
     */
    public function messageToArray(Message $message): array
    {
        $product = $message->getMessage();
        return $this->serializer->unserialize($product);
    }
}