<?php

namespace Webjump\DrupalStock\Plugin;

use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\CatalogInventory\Api\Data\StockItemInterface;
use Magento\CatalogInventory\Model\Stock\StockItemRepository;
use Magento\Framework\Serialize\SerializerInterface;
use Webjump\DrupalStock\Api\MessageInterface;
use Webjump\DrupalStock\Api\QueueInterface;

class SaveStockPlugin
{
    /**
     * @var MessageInterface
     */
    private $message;

    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * @var QueueInterface
     */
    private $queue;

    /**
     * @var ProductRepositoryInterface
     */
    private $productRepository;

    /**
     * @param MessageInterface $message
     * @param SerializerInterface $serializer
     * @param QueueInterface $queue
     * @param ProductRepositoryInterface $productRepository
     */
    public function __construct(
        MessageInterface $message,
        SerializerInterface $serializer,
        QueueInterface $queue,
        ProductRepositoryInterface $productRepository
    ) {
        $this->message = $message;
        $this->serializer = $serializer;
        $this->queue = $queue;
        $this->productRepository = $productRepository;
    }

    public function afterSave(StockItemRepository $subject, $result, StockItemInterface $stockItem)
    {
        $product = $this->productRepository->getById($stockItem->getProductId());
        $message = $this->message->prepareMessage($product);
        $this->message->setMessage($this->serializer->serialize($message));
        $this->queue->send($this->message);

        return $result;
    }
}
