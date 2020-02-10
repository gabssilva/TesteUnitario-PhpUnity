<?php
/**
 * @author      Webjump Core Team <dev@webjump.com.br>
 * @copyright   2018 Webjump (http://www.webjump.com.br)
 * @license     http://www.webjump.com.br  Copyright
 *
 * @link        http://www.webjump.com.br
 */

namespace Webjump\DrupalStock\Model;

use Webjump\DrupalStock\Api\MessageInterface;

class Message implements MessageInterface
{
    /**
     * @var string
     */
    private $message;

    /**
     * {@inheritdoc}
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * {@inheritdoc}
     */
    public function setMessage($message)
    {
        return $this->message = $message;
    }

    /**
     * {@inheritdoc}
     */
    public function prepareMessage($product)
    {
        $stockItem = $product->getExtensionAttributes()->getStockItem();
        $message = [
            'sku' => $product->getSku(),
            'price' => $product->getPrice(),
            'stock' => $stockItem->getQty(),
            'type' => $product->getTypeId(),
            'retries' => 0
        ];
        return $message;
    }
}